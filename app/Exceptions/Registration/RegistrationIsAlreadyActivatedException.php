<?php

namespace App\Exceptions\Registration;

class RegistrationIsAlreadyActivatedException extends RegistrationException
{
    const CODE = '2003';

    /**
     * RegistrationIsAlreadyActivatedException constructor.
     *
     * @param string $message
     */
    public function __construct($message)
    {
        parent::__construct($message, self::CODE);
    }
}
