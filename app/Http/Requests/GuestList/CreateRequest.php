<?php

namespace App\Http\Requests\GuestList;

use App\Http\Requests\Request;

class CreateRequest extends Request
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
            'name' => 'required|min:3',
            'description' => 'sometimes|min:3',

            'guest.0' => 'required',
            'guest.0.name' => 'required|min:3',
            'guest.*.name' => 'required_with:guest.*.notes|min:3',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'guest.0.required' => 'The guest list requires at least one guest.',
            'guest.*.name.required' => 'The guest requires a name.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'guest.*.name' => 'name of guest',
        ];
    }
}
