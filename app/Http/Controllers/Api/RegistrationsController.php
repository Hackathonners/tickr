<?php

namespace App\Http\Controllers\Api;

use Auth;
use App\Karina\User;
use App\Support\Str;
use App\Karina\Event;
use App\Mail\TicketMail;
use App\Karina\Registration;
use Illuminate\Http\Request;
use App\Karina\RegistrationType;
use Illuminate\Support\Facades\DB;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Support\Facades\Mail;
use App\Transformers\RegistrationTransformer;
use App\Http\Requests\Registration\CreateRequest;
use App\Exceptions\Registration\RegistrationException;
use App\Exceptions\Registration\RegistrationIsAlreadyActivatedException;

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

        $request->replace([
            'search' => Str::searchable($request->get('search', '')),
        ]);

        $search = $request->get('search');

        $registrations = DB::transaction(function () use ($eventId, $limit, $search) {
            $event = Event::findOrFail($eventId);
            $this->authorize('handle', $event);

            $registrations = $event->registrations()
                                   ->filterByNameEmailAndNotes($search)
                                   ->with('user')
                                   ->orderBy('created_at', 'desc');

            $limit = $limit > 0 && $limit <= 20 ? $limit : null;

            return $registrations->paginate($limit);
        });

        return $this->respondWith($registrations, new RegistrationTransformer, ['user']);
    }

    /**
     * Display history of a given user in events of the authenticated owner.
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
                $owner = Auth::user();
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

                $this->sendTicketEmail($registration);

                return Registration::with(['user', 'event', 'registrationType'])->find($registration->id);
            });

            return $this->respondWith($registration, new RegistrationTransformer);
        } catch (RegistrationException $e) {
            return $this->errorForbidden($e->getMessage());
        }
    }

    /**
     * Activate a registration.
     *
     * @param $hashId
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

    /**
     * Resend the email of the given registration.
     *
     * @param $hashId
     * @return \Illuminate\Http\Response
     */
    public function resendEmail($hashId)
    {
        $id = Hashids::decode($hashId);
        $id = count($id) > 0 ? $id[0] : $id;

        try {
            DB::transaction(function () use ($id) {
                $registration = Registration::with(['event', 'user'])
                                    ->findOrFail($id);
                $this->authorize('handle', $registration->event);

                if ($registration->activated) {
                    throw new RegistrationIsAlreadyActivatedException('Cannot resend email because this ticket is already activated');
                }

                $this->sendTicketEmail($registration);
            });

            return $this->respondWithArray([
                'success' => true,
                'message' => 'Successfully sent the ticket email.',
            ]);
        } catch (\LogicException $e) {
            return $this->errorForbidden($e->getMessage());
        }
    }

    /**
     * Remove the specified registration from storage.
     *
     * @param $hashId
     * @return \Illuminate\Http\Response
     */
    public function destroy($hashId)
    {
        $id = Hashids::decode($hashId);
        $id = count($id) > 0 ? $id[0] : $id;

        try {
            DB::transaction(function () use ($id) {
                $registration = Registration::with('event')->findOrFail($id);
                $this->authorize('handle', $registration->event);

                return $registration->delete();
            });

            return $this->respondWithArray([
                'success' => true,
                'message' => 'Successfully deleted the registration.',
            ]);
        } catch (\LogicException $e) {
            return $this->errorForbidden($e->getMessage());
        }
    }

    private function sendTicketEmail(Registration $registration)
    {
        Mail::to($registration->user->email, $registration->user->name)
              ->send(new TicketMail($registration));
    }
}
