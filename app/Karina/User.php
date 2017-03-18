<?php

namespace App\Karina;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Get registrations in events that is owned by given user.
     *
     * @param \App\Karina\User $owner
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function registrationsWithEventOwner(User $owner)
    {
        return $this->registrations()
                ->leftJoin(
                    'events',
                    'registrations.event_id',
                    '=',
                    'events.id'
                )
                ->where('events.user_id', $owner->id)
                // Force select registration data, otherwise it will have conflicts in "user_id" field.
                ->select('registrations.*');
    }

    /**
     * Get registrations of this user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMay
     */
    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }

    /**
     * Get events that this user owns.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMay
     */
    public function events()
    {
        return $this->hasMany(Event::class);
    }

    /**
     * Get guestlists that this user owns.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function guests()
    {
        return $this->hasManyThrough(Guest::class, GuestList::class);
    }
}
