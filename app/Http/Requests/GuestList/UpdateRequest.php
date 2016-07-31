<?php

namespace App\Http\Requests\GuestList;

class UpdateRequest extends CreateRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->sanitize();

        return [
            'name' => 'sometimes|min:3',
            'description' => 'sometimes|min:3',

            'guest.0' => 'sometimes',
            'guest.0.name' => 'sometimes|min:3',
            'guest.0.email' => 'sometimes|email',
            'guest.*.name' => 'sometimes|required_with:guest.*.email|min:3',
            'guest.*.email' => 'sometimes|required_with:guest.*.name|email',
        ];
    }
}
