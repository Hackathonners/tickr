<?php

namespace App\Http\Requests\Registration;

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
            'email' => 'required|email',
            'notes' => 'sometimes',
            'fined' => 'required',
            'registration_type' => 'required',
        ];
    }
}
