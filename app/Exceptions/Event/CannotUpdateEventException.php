<?php

namespace App\Exceptions\Event;

class CannotUpdateEventException extends \LogicException
{
    const CODE = '1001';

    /**
     * CannotUpdateEventException constructor.
     *
     * @param string $message
     */
    public function __construct($message)
    {
        parent::__construct($message, self::CODE);
    }
}
