<?php

namespace App\Karina;

use App\Exceptions\Registration\UserIsAlreadyRegisteredOnEventException;
use App\Exceptions\Registration\RegistrationIsAlreadyActivatedException;
use App\Exceptions\Registration\RegistrationOnPastEventException;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Vinkla\Hashids\Facades\Hashids;

class Registration extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fined', 'notes',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['activation_code'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['activated_at'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'fined' => 'bool',
        'activated' => 'bool',
    ];

    /**
     * Associate information to this registration.
     *
     * @param Event $event
     * @param User $user
     * @param RegistrationType $registrationType
     * @param bool $fined
     * @return $this
     *
     * @throws UserIsAlreadyRegisteredOnEventException
     */
    public function register(Event $event, User $user, RegistrationType $registrationType)
    {
        if ($event->isPast()) {
            throw new RegistrationOnPastEventException('Cannot add registrations to past events.');
        }

        $alreadyRegistered = self::where([
            'event_id' => $event->id,
            'user_id' => $user->id,
        ])->exists();

        if ($alreadyRegistered) {
            throw new UserIsAlreadyRegisteredOnEventException('Email '.$user->email.' is already registered on event '.$event->title.'.');
        }

        $this->activated = false;
        $this->activation_code = str_random(32);
        $this->event()->associate($event);
        $this->registrationType()->associate($registrationType);
        $this->user()->associate($user);

        return $this;
    }

    /**
     * Get the registrations's total.
     *
     * @return string
     */
    public function getTotalAttribute($value)
    {
        $total = $this->registrationType->price;

        $total += $this->fined ? $this->registrationType->fine : 0;

        return $total;
    }

    /**
     * Get the registrations's hash id.
     *
     * @return string
     */
    public function getHashidAttribute($value)
    {
        return Hashids::encode($this->id);
    }

    /**
     * Activate this registration.
     *
     * @return $this
     */
    public function activate()
    {
        if ($this->activated) {
            throw new RegistrationIsAlreadyActivatedException('This ticket is already activated');
        }

        $this->activated = true;
        $this->activated_at = Carbon::now(config('app.timezone'));

        return $this->save();
    }

    /**
     * Get event of this registration.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Get user of this registration.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get registration type of this registration.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function registrationType()
    {
        return $this->belongsTo(RegistrationType::class);
    }
}
