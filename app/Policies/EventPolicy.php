<?php

namespace App\Policies;

use App\Karina\User;
use App\Karina\Event;
use Illuminate\Auth\Access\HandlesAuthorization;

class EventPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the given event can be handled by the user.
     *
     * @param  User $user
     * @param Event $event
     * @return bool
     */
    public function handle(User $user, Event $event)
    {
        return $user->id === $event->user_id;
    }
}
