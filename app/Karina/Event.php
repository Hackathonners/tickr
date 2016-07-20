<?php

namespace App\Karina;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'description', 'place', 'start_at', 'end_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'user'
    ];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['registrationTypes'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['start_at', 'end_at'];

    /**
     * Get user that owns this event.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get registration types for this event.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function registrationTypes()
    {
        return $this->hasMany(RegistrationType::class);
    }
}
