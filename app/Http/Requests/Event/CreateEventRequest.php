<?php

namespace App\Http\Requests\Event;

use App\Http\Requests\Request;

class CreateEventRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->sanitize();

        return [
            'title' => 'required|min:3',
            'description' => 'sometimes|min:3',
            'place' => 'required|min:3',
            'start_at' => 'required|date_format:Y-m-d H:i:s|after:now',
            'end_at' => 'required|date_format:Y-m-d H:i:s|after:start_at',

            'registration' => 'required|array|min:1',
            'registration.0.type' => 'required|min:3',
            'registration.0.price' => 'required|numeric|min:0.00',
            'registration.0.fine' => 'numeric|min:0.00',
            'registration.*.type' => 'required_with:registration.*.price|min:3',
            'registration.*.price' => 'required_with:registration.*.type|numeric|min:0.00',
            'registration.*.fine' => 'numeric|min:0.00',
        ];
    }
}
