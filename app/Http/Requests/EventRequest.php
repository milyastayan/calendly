<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class EventRequest extends ParentRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'user_id' => 'integer',
            'description' => 'nullable',
            'duration' => 'required|integer',
            'start_date' => 'required',
            'end_date' => 'required',
            'location' => 'required',
//            'custom_link' => 'required',
            'color' => 'required',
            'status' => 'bool',
        ];
    }
}
