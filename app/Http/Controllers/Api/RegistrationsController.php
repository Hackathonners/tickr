<?php

namespace App\Http\Controllers\Api;

use Auth;
use Illuminate\Http\Request;
use App\Karina\Event;
use App\Http\Requests\Registration\CreateRequest;
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
     * Display a listing of the registrations of a given event.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($eventId, Request $request)
    {
        $limit = $request->get('limit', 0);

        $registrations = DB::transaction(function () use ($eventId, $limit) {
            $event = Event::findOrFail($eventId);
            $this->authorize('handle', $event);

            $registrations = $event->registrations()->with('user');
            $limit = $limit > 0 && $limit <= 20 ? $limit : null;

            return $registrations->paginate($limit);
        });

        return $this->respondWith($registrations, new RegistrationTransformer, ['user']);
    }

    /**
     * Display history of the registration of user in events of authenticated owner.
     *
     * @return \Illuminate\Http\Response
     */
    public function registry($user, Request $request)
    {
        $limit = $request->get('limit', 0);

        $registrations = DB::transaction(function () use ($user, $limit) {
            $owner = Auth::user();
            $user = User::findOrFail($user);

            $registrations = $user->registrationsWithEventOwner($owner);
            $limit = $limit > 0 && $limit <= 20 ? $limit : null;

            return $registrations->paginate($limit);
        });

        return $this->respondWith($registrations, new RegistrationTransformer);
    }

    /**
     * Store a newly created registration in storage.
     *
     * @param CreateRegistrationRequest $request
     * @param $eventId
     * @return \Illuminate\Http\Response
     */
    public function store(CreateRequest $request, $eventId)
    {
        try {
            $registration = DB::transaction(function () use ($request, $eventId) {
                $event = Event::findOrFail($eventId);
                $this->authorize('handle', $event);

                $user = User::where(['email' => $request->input('email')])->first();
                $registrationType = RegistrationType::findOrFail($request->input('registration_type'));

                if (! $user) {
                    $user = new User;
                    $user->fill($request->all());
                    $user->password = bcrypt(str_random(10));
                    $user->save();
                }

                $registration = new Registration();
                $registration->register($event, $user, $registrationType);
                $registration->fill($request->all());
                $registration->save();

                Mail::send('emails.ticket', compact('registration'), function ($m) use ($registration) {
                    $m->from(config('mail.from.address'), config('mail.from.name'))
                      ->to($registration->user->email, $registration->user->name)
                      ->subject('Ticket for '.$registration->event->title);
                });

                return Registration::with(['user', 'event', 'registrationType'])->find($registration->id);
            });

            return $this->respondWith($registration, new RegistrationTransformer);
        } catch (\LogicException $e) {
            return $this->errorForbidden($e->getMessage());
        }
    }

    /**
     * Activate a registration.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function activate($hashId, $token)
    {
        $id = Hashids::decode($hashId);
        $id = count($id) > 0 ? $id[0] : $id;

        try {
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
        } catch (\LogicException $e) {
            return $this->errorForbidden($e->getMessage());
        }
    }
}
