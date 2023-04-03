<?php

namespace App\Http\Resources;


use App\Models\User;

class UserResource extends Resource
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
        /** @var User|self $this */

        $array = [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
        ];

        return $array;
    }
}
