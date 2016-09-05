<?php

namespace App\Karina;

use App\Exceptions\Event\UserIsAlreadyRegisteredOnEventException;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Registration extends Model
{
    use SoftDeletes;

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
    public function register(Event $event, User $user, RegistrationType $registrationType, $fined = false, $notes = "")
    {
        $alreadyRegistered = self::where([
            'event_id' => $event->id,
            'user_id' => $user->id,
        ])->exists();

        if ($alreadyRegistered) {
            throw new UserIsAlreadyRegisteredOnEventException('Email '.$user->email.' is already registered on event '.$event->title.'.');
        }

        $this->fined = $fined;
        $this->notes = $notes;
        $this->activated = false;
        $this->activation_code = str_random(10);
        $this->event()->associate($event);
        $this->registrationType()->associate($registrationType);
        $this->user()->associate($user);

        return $this;
    }

    /**
     * Activate this registration.
     *
     * @return $this
     */
    public function activate()
    {
        if ($this->activated) {
            return $this;
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
