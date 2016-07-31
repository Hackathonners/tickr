<?php

namespace App\Exceptions\Event;

class UserIsAlreadyRegisteredOnEventException extends \LogicException
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
