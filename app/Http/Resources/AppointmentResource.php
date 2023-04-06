<?php

namespace App\Http\Resources;


use App\Models\Event;
use App\Models\User;

class AppointmentResource extends Resource
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
            'event' => new EventResource($this->event),
            'guest_name' => $this->guest_name,
            'guest_email' => $this->guest_email,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'meeting_link' => $this->meeting_link,

        ];

        return $array;
    }
}
