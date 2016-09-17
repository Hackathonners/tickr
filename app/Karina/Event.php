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
     * Check if this event has registrations.
     *
     * @return bool
     */
    public function hasRegistrations()
    {
        return $this->registrations()->count() > 0;
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
     * Get statistics of registration types of this event.
     *
     * @return array
     */
    public function getRegistrationTypeStats()
    {
        $statistics = $this->registrationTypes()
                        ->rightJoin(
                            'registrations',
                            'registrations.registration_type_id',
                            '=',
                            'registration_types.id'
                        )
                        ->select('registration_types.id')
                        ->selectRaw('count(*) as registrations')
                        ->selectRaw('sum(case when fined=true then (price + fine) else (price) end) as income')
                        ->selectRaw('count(case when activated=true then 1 end) as participations')
                        ->groupBy('registration_types.id');

        return $statistics->get();
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
     * Scope a query to only include past events.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePast($query)
    {
        return $query->where('end_at', '<', Carbon::now()->toDateString());
    }

    /**
     * Scope a query to only include only running or future events.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('end_at', '>=', Carbon::now()->toDateString());
    }

    /**
     * Get registrations of this event.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function registrations()
    {
        return $this->hasMany(Registration::class);
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
            if ($this->isDirty(['title', 'start_at']) && $this->hasRegistrations()) {
                throw new CannotUpdateEventException('Cannot update an event that already has registrations.');
            }

            if ($this->isDirty('start_at') && $this->isPastDate($this->getOriginal('start_at'))) {
                throw new CannotUpdateEventException('Cannot update start date an event that is already started.');
            }
        }

        return parent::save($options);
    }
}
