<?php

namespace App\Http\Resources;


use App\Models\Event;
use App\Models\User;

class EventResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        /** @var Event|self $this */

        $array = [
            'id' => $this->id,
            'title' => $this->title,
            'user' => new UserResource($this->user),
            'description' => $this->description,
            'duration' => $this->duration,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'location' => $this->location,
            'custom_link' => $this->custom_link,
            'color' => $this->color,
            'status' => $this->status,
        ];

        return $array;
    }
}
