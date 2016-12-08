<?php

namespace App\Exceptions\Registration;

class UserIsAlreadyRegisteredOnEventException extends RegistrationException
{
    const CODE = '2001';

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
