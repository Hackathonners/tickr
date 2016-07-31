<?php

namespace App\Http\Requests\Event;

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
            'title' => 'required|min:3',
            'description' => 'sometimes|min:3',
            'place' => 'required|min:3',
            'start_at' => 'required|date_format:Y-m-d H:i:s|after:now',
            'end_at' => 'required|date_format:Y-m-d H:i:s|after:start_at',

            'registration.0' => 'required',
            'registration.0.type' => 'required|min:3',
            'registration.0.price' => 'required|numeric|min:0.00',
            'registration.0.fine' => 'numeric|min:0.00',
            'registration.*.type' => 'required_with:registration.*.price|min:3',
            'registration.*.price' => 'required_with:registration.*.type|numeric|min:0.00',
            'registration.*.fine' => 'numeric|min:0.00',
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
            'registration.0.required' => 'The event requires at least one registration type.',
            'registration.*.type.required' => 'The registration types require a name.',
            'registration.*.price.required' => 'The registration types require a price.',
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
            'registration.*.type' => 'name of registration type',
            'registration.*.price' => 'price',
        ];
    }
}
