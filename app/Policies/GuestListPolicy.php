<?php

namespace App\Policies;

use App\Karina\User;
use App\Karina\GuestList;
use Illuminate\Auth\Access\HandlesAuthorization;

class GuestListPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the given event can be handled by the user.
     *
     * @param  User $user
     * @param GuestList $guestList
     * @return bool
     */
    public function handle(User $user, GuestList $guestList)
    {
        return $user->id === $guestList->user_id;
    }
}
