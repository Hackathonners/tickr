<?php

namespace App\Exceptions\Registration;

class RegistrationOnPastEventException extends \LogicException
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
