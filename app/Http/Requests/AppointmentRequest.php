<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class AppointmentRequest extends ParentRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'event_id' => 'integer',
            'guest_name' => 'required|string|max:255',
            'guest_email' => 'required|string|email|max:255',
            'start_time' => 'required',
            'end_time' => '',
        ];
    }
}
