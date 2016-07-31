<?php

namespace App\Karina;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
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
     * Get events that this user owns.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMay
     */
    public function events()
    {
        return $this->hasMany(Event::class);
    }
}
