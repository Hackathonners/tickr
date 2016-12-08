<?php

namespace App\Exceptions\Registration;

class RegistrationOnPastEventException extends RegistrationException
{
    const CODE = '2002';

    /**
     * UserIsAlreadyRegisteredOnEventException constructor.
     *
     * @param string $message
     */
    public function __construct($message)
    {
        parent::__construct($message, self::CODE);
    }
}
