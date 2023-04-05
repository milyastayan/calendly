<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\EventRequest;
use App\Http\Resources\EventResource;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return EventResource::collection($request->user()->events()->orderBy('created_at',
            'DESC')->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(EventRequest $request)
    {
        $request->merge(['user_id' => Auth::user()->id]);

        $title = $request->input('title');
        $customLink = str_replace(' ', '-', strtolower($title));
        while (Event::where('custom_link', $customLink)->exists()) {
            $customLink = $customLink . rand(0, 9);
        }
        $request->merge(['custom_link' => $customLink]);

        $event = Event::create($request->all());

        return new EventResource($event);

    }

    public function show(Event $event)
    {
        return new EventResource($event);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Event $event
     * @return \Illuminate\Http\Response
     */
    public function update(EventRequest $request, Event $event)
    {
        Auth::user()->events()->findOrFail($event->id);

        $event->update($request->all());

        return new EventResource($event);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Event $event
     * @return \Illuminate\Http\Response
     */
    public function destroy(Event $event)
    {
        $event->delete();
        return $this->respondWithSuccess();
    }
}
