<?php

namespace App\Exceptions\Registration;

abstract class RegistrationException extends \LogicException
{
    /**
     * RegistrationException constructor.
     *
     * @param string $message
     */
    public function __construct($message)
    {
        parent::__construct($message);
    }
}
