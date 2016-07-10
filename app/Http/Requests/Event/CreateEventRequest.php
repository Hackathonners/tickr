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
            // 'prices' => 'required|array|min:1|max:3',
            // 'prices.0.name' => 'required|min:3',
            // 'prices.0.price' => 'required|numeric|min:0.00',
            // 'prices.*.name' => 'required_with:prices.*.price|min:3',
            // 'prices.*.price' => 'required_with:prices.*.name|numeric|min:0.00',
            'start_at' => 'required|date_format:Y-m-d H:i:s|after:now',
            'end_at' => 'required|date_format:Y-m-d H:i:s|after:start_at',
        ];
    }
}
