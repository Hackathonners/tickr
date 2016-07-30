<?php

namespace App\Karina;

use App\Exceptions\Event\CannotUpdateEventException;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'description', 'place', 'start_at', 'end_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'user',
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
    protected $dates = ['start_at', 'end_at', 'deleted_at'];

    /**
     * Check if this event has sold tickets.
     *
     * @return bool
     */
    public function hasTickets()
    {
        return false;
    }

    /**
     * Check if this event is already started.
     *
     * @return bool
     */
    public function isStarted()
    {
        return $this->isPastDate($this->$start_at);
    }

    /**
     * Check if this event is already started.
     *
     * @param Carbon $date
     * @return bool
     */
    private function isPastDate($date)
    {
        if (!($date instanceof Carbon)) {
            $date = Carbon::parse($date);
        }

        return $date->isPast();
    }

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

    /**
     * @inheritdoc
     *
     * @throws \Exception
     */
    public function save(array $options = [])
    {
        if ($this->exists) {
            //if ($this->isDirty('title') && $this->hasRegistrations()) {
            //    throw new CannotUpdateEventException('Cannot update an event that already has registrations.');
            //}

            if ($this->isDirty('start_at') && $this->isPastDate($this->getOriginal('start_at'))) {
                throw new CannotUpdateEventException('Cannot update start date an event that is already started.');
            }
        }

        return parent::save($options);
    }
}
