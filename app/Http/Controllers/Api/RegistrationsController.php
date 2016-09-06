<?php

namespace App\Http\Controllers\Api;

use App\Karina\Event;
use App\Exceptions\Event\UserIsAlreadyRegisteredOnEventException;
use App\Http\Requests\Registration\CreateRegistrationRequest;
use App\Karina\Registration;
use App\Karina\RegistrationType;
use App\Karina\User;
use App\Transformers\RegistrationTransformer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use Vinkla\Hashids\Facades\Hashids;

class RegistrationsController extends ApiController
{
    /**
     * Store a newly created registration in storage.
     *
     * @param CreateRegistrationRequest $request
     * @param $eventId
     * @return \Illuminate\Http\Response
     */
    public function store(CreateRegistrationRequest $request, $eventId)
    {
        try {
            $registration = DB::transaction(function () use ($request, $eventId) {
                $event = Event::findOrFail($eventId);
                $this->authorize('handle', $event);

                $user = User::where(['email' => $request->input('email')])->first();
                $registrationType = RegistrationType::findOrFail($request->input('registration_type'));

                if (!$user) {
                    $user = new User;
                    $user->fill($request->all());
                    $user->save();
                }

                $registration = new Registration();
                $registration->register($event, $user, $registrationType);
                $registration->fill($request->all());
                $registration->save();

                return Registration::with(['user', 'event', 'registrationType'])->find($registration->id);
            });

            Mail::send('emails.ticket', compact('registration'), function ($m) use ($registration) {
                $m->from(config('mail.from.address'), config('mail.from.name'))
                  ->to($registration->user->email, $registration->user->name)
                  ->subject('Ticket for '.$registration->event->title);
            });

            return $this->respondWith($registration, new RegistrationTransformer);
        } catch (UserIsAlreadyRegisteredOnEventException $e) {
            return $this->errorForbidden($e->getMessage());
        }
    }

    /**
     * Activate a registration.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function activate($id)
    {
        $token = Input::get('token');

        if (!$token) {
            return $this->errorWrongArgs('Activation code was not provided.');
        }

        $idDecoded = Hashids::decode($id);
        $id = count($idDecoded) > 0 ? $idDecoded[0] : $id;

        DB::transaction(function () use ($id, $token) {
            $registration = Registration::where(['activation_code' => $token])
                                ->with('event')
                                ->findOrFail($id);
            $this->authorize('handle', $registration->event);
            $registration->activate();
        });

        return $this->respondWithArray([
            'success' => true,
            'message' => 'Successfully activated registration.',
        ]);
    }
}
