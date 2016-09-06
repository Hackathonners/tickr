<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Controllers\Api\ApiController;
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

    /**
     * Get the proper failed validation response for the request.
     *
     * @param  array  $errors
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function response(array $errors)
    {
        if (($this->ajax() && !$this->pjax()) || $this->wantsJson()) {
            return (new ApiController)->errorWrongArgs($errors);
        }

        return $this->redirector->to($this->getRedirectUrl())
                                        ->withInput($this->except($this->dontFlash))
                                        ->withErrors($errors, $this->errorBag);
    }
}
