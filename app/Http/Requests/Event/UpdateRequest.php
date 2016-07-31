<?php

namespace App\Http\Requests\Event;

use App\Http\Requests\Request;

class UpdateRequest extends Request
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
            'place' => 'sometimes|min:3',
            'start_at' => 'sometimes|date_format:Y-m-d H:i:s|after:now',
            'end_at' => 'sometimes|date_format:Y-m-d H:i:s|after:start_at',
        ];
    }
}
