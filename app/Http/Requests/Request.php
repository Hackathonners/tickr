<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Support\Arr;

abstract class Request extends FormRequest
{
    /**
     * Sanitize the input of this request.
     *
     * @param bool $trim
     */
    public function sanitize($trim = true)
    {
        $input = Arr::clean($this->all(), $trim);
        $this->replace($input);
    }
}
