<?php

use Carbon\Carbon;
use App\Karina\User;
use App\Karina\Event;
use App\Mail\TicketMail;
use App\Karina\Registration;
use App\Karina\RegistrationType;
use Vinkla\Hashids\Facades\Hashids;
use App\Transformers\RegistrationTransformer;
use Symfony\Component\HttpFoundation\Response;

class RegistrationsTest extends ApiTestCase
{
    public function testShowRegistrationsOfEvent()
    {
        // Prepare data
        $limit = 5;
        $user = factory(User::class)->create();

        $event = factory(Event::class)->create([
            'user_id' => $user->id,
        ]);
        $registrationType = factory(RegistrationType::class)->create([
            'event_id' => $event->id,
        ]);
        $participants = factory(User::class, 10)->create()->each(function ($user) use ($event, $registrationType) {
            factory(Registration::class)->create([
                'user_id' => $user->id,
                'event_id' => $event->id,
                'registration_type_id' => $registrationType->id,
            ]);
        });

        // Perform task
        $this->actingAs($user)
            ->json('GET', '/events/'.$event->id.'/registrations/?limit=5');

        // Assertions
        $this->assertResponseOk();
        $this->assertEquals(10, Registration::count(), 'Registrations were not stored in database');

        $this->seeJsonEquals(Fractal::collection(Registration::paginate($limit), new RegistrationTransformer)->toArray());

        $this->seeJson([
            'total' => 10,
            'count' => 5,
            'per_page' => $limit,
        ]);
    }

    public function testShowUserRegistrationsInEventsOfAuthenticatedOwner()
    {
        // Prepare data
        $limit = 5;
        $owner1 = factory(User::class)->create();
        $owner2 = factory(User::class)->create();

        $user1 = factory(User::class)->create();
        $user2 = factory(User::class)->create();

        $eventOwner1 = factory(Event::class)->create([
            'user_id' => $owner1->id,
        ]);

        $eventOwner2 = factory(Event::class)->create([
            'user_id' => $owner2->id,
        ]);

        $registrationTypeEvent1 = factory(RegistrationType::class)->create([
            'event_id' => $eventOwner1->id,
        ]);

        $registrationTypeEvent2 = factory(RegistrationType::class)->create([
            'event_id' => $eventOwner2->id,
        ]);

        // Create registrations
        $registrationEvent1User1 = factory(Registration::class)->create([
            'user_id' => $user1->id,
            'event_id' => $eventOwner1->id,
            'registration_type_id' => $registrationTypeEvent1->id,
        ]);

        $registrationEvent2User1 = factory(Registration::class)->create([
            'user_id' => $user1->id,
            'event_id' => $eventOwner2->id,
            'registration_type_id' => $registrationTypeEvent2->id,
        ]);

        $registrationEvent1User2 = factory(Registration::class)->create([
            'user_id' => $user2->id,
            'event_id' => $eventOwner1->id,
            'registration_type_id' => $registrationTypeEvent1->id,
        ]);

        // Perform task
        $this->actingAs($owner1)
            ->json('GET', '/users/'.$user1->id.'/registrations/?limit=5');

        // Assertions
        $this->assertResponseOk();
        $this->assertEquals(3, Registration::count(), 'Registrations were not stored in database');
        $this->assertEquals(
            1,
            $user1->registrationsWithEventOwner($owner1)->count(),
            'User 1 is not registered in an event of owner 1'
        );
        $this->assertEquals(
            1,
            $user1->registrationsWithEventOwner($owner2)->count(),
            'User 1 is not registered in an event of owner 2'
        );

        $this->seeJsonEquals(Fractal::collection(
            Registration::find(['id' => $registrationEvent1User1->id]),
            new RegistrationTransformer)
        ->toArray());

        $this->seeJson([
            'total' => 1,
            'count' => 1,
            'per_page' => $limit,
        ]);
    }

    public function testCreateRegistration()
    {
        // Prepare data
        $user = factory(User::class)->create([
            'email' => 'test@test.com',
        ]);

        $startAt = (new DateTime())->modify('+1 day');
        $endAt = (new DateTime())->modify('+5 day');
        $event = factory(Event::class)->create([
            'user_id' => $user->id,
            'title' => 'Event name',
            'description' => 'Event description',
            'place' => 'Place',
            'start_at' => $startAt->format('Y-m-d H:i:s'),
            'end_at' => $endAt->format('Y-m-d H:i:s'),
        ]);
        $event->each(function ($event) {
            factory(RegistrationType::class, 2)->create([
                'event_id' => $event->id,
            ]);
        });

        $data = [
            'name' => 'aaa',
            'email' => 'a@a.com',
            'registration_type' => $event->registrationTypes()->first()->id,
            'fined' => false,
            'notes' => 'Dummy User',
        ];

        // Pretend emails
        Mail::fake();

        // Perform task
        $this->actingAs($user)
            ->json('POST', '/events/'.$event->id.'/registrations', $data);

        // Assertions
        $this->assertResponseOk();
        $this->assertEquals(2, User::count(), 'Participant was not stored in database');
        $this->assertEquals(1, Registration::count(), 'Registration was not stored in database');

        $registration = Registration::first();
        $this->seeJson(Fractal::item(Registration::first(), new RegistrationTransformer)->toArray());
        $this->seeJson([
            'fined' => false,
            'activated' => false,
            'notes' => 'Dummy User',
        ]);

        // Assert email
        $this->assertTicketEmail($registration);
    }

    public function testCreateRegistrationForExistingUser()
    {
        // Prepare data
        $user = factory(User::class)->create();
        $participant = factory(User::class)->create();

        $event = factory(Event::class)->create([
            'user_id' => $user->id,
        ]);

        $event->each(function ($event) {
            factory(RegistrationType::class, 2)->create([
                'event_id' => $event->id,
            ]);
        });

        $data = [
            'name' => $participant->name,
            'email' => $participant->email,
            'registration_type' => $event->registrationTypes()->first()->id,
            'fined' => true,
        ];

        // Pretend emails
        Mail::fake();

        // Perform task
        $this->actingAs($user)
            ->json('POST', '/events/'.$event->id.'/registrations', $data);

        // Assertions
        $this->assertResponseOk();
        $this->assertEquals(2, User::count(), 'Users are not strict to owner and participant.');
        $this->assertEquals(1, Registration::count(), 'Registration was not stored in database');

        $registration = Registration::first();
        $this->seeJson(Fractal::item($registration, new RegistrationTransformer)->toArray());
        $this->seeJson([
            'fined' => true,
            'activated' => false,
        ]);

        // Assert email
        $this->assertTicketEmail($registration);
    }

    public function testCreateRegistrationWithPreviousDeletedData()
    {
        // Prepare data
        $user = factory(User::class)->create([
            'email' => 'test@test.com',
        ]);
        $participant = factory(User::class)->create();

        $startAt = (new DateTime())->modify('+1 day');
        $endAt = (new DateTime())->modify('+5 day');
        $event = factory(Event::class)->create([
            'user_id' => $user->id,
            'title' => 'Event name',
            'description' => 'Event description',
            'place' => 'Place',
            'start_at' => $startAt->format('Y-m-d H:i:s'),
            'end_at' => $endAt->format('Y-m-d H:i:s'),
        ]);

        $registrationType = factory(RegistrationType::class)->create([
            'event_id' => $event->id,
        ]);

        $registration = factory(Registration::class)->create([
            'event_id' => $event->id,
            'registration_type_id' => $registrationType->id,
            'user_id' => $participant->id,
        ]);

        // Delete registration
        $registration->delete();

        $data = [
            'name' => $participant->name,
            'email' => $participant->email,
            'registration_type' => $event->registrationTypes()->first()->id,
            'fined' => false,
            'notes' => 'Dummy User',
        ];

        // Pretend emails
        Mail::fake();

        // Perform task
        $this->actingAs($user)
            ->json('POST', '/events/'.$event->id.'/registrations', $data);

        // Assertions
        $this->assertResponseOk();
        $this->assertEquals(1, Registration::count(), 'Registration was not stored in database');

        $registration = Registration::first();
        $this->seeJson(Fractal::item($registration, new RegistrationTransformer)->toArray());
        $this->seeJson([
            'fined' => false,
            'activated' => false,
            'notes' => 'Dummy User',
        ]);

        // Assert email
        $this->assertTicketEmail($registration);
    }

    public function testCreateRegistrationForPastEvent()
    {
        // Prepare data
        $user = factory(User::class)->create();
        $participant = factory(User::class)->create();

        $startAt = (new DateTime())->modify('-10 day');
        $endAt = (new DateTime())->modify('-5 day');
        $event = factory(Event::class)->create([
            'user_id' => $user->id,
            'title' => 'Event name',
            'description' => 'Event description',
            'place' => 'Place',
            'start_at' => $startAt->format('Y-m-d H:i:s'),
            'end_at' => $endAt->format('Y-m-d H:i:s'),
        ]);

        $event->each(function ($event) {
            factory(RegistrationType::class, 2)->create([
                'event_id' => $event->id,
            ]);
        });

        $data = [
            'name' => $participant->name,
            'email' => $participant->email,
            'registration_type' => $event->registrationTypes()->first()->id,
            'fined' => true,
        ];

        // Pretend emails
        Mail::fake();

        // Perform task
        $this->actingAs($user)
            ->json('POST', '/events/'.$event->id.'/registrations', $data);

        // Assertions
        $this->assertEquals(0, Registration::count(), 'Registration was stored in database');
        $this->assertResponseStatus(Response::HTTP_FORBIDDEN);
        $this->seeJsonStructure([
            'error' => [
                'code', 'http_code', 'messages',
            ],
        ]);

        // Assert email
        Mail::assertNotSent(TicketMail::class);
    }

    public function testActivateRegistration()
    {
        // Prepare data
        $user = factory(User::class)->create();
        $participant = factory(User::class)->create();

        $event = factory(Event::class)->create([
            'user_id' => $user->id,
        ]);

        $registrationType = factory(RegistrationType::class)->create([
            'event_id' => $event->id,
        ]);

        $registration = factory(Registration::class)->create([
            'event_id' => $event->id,
            'registration_type_id' => $registrationType->id,
            'user_id' => $participant->id,
        ]);

        $token = $registration->activation_code;

        // Perform task
        $this->actingAs($user)
            ->json('POST', '/registrations/'.Hashids::encode($registration->id).'/activate/'.$token);

        // Assertions
        $this->assertResponseOk();
        $this->assertEquals(1, Registration::where(['activated' => true])->count(), 'Registration was not activated.');
        $this->assertNotNull(Registration::first()->activated_at, 'Activation timestamp was not stored.');
        $this->seeJson([
            'success' => true,
        ]);
    }

    public function testActivateRegistrationAlreadyActivated()
    {
        // Prepare data
        $user = factory(User::class)->create();
        $participant = factory(User::class)->create();

        $event = factory(Event::class)->create([
            'user_id' => $user->id,
        ]);

        $registrationType = factory(RegistrationType::class)->create([
            'event_id' => $event->id,
        ]);

        $registration = factory(Registration::class)->create([
            'event_id' => $event->id,
            'registration_type_id' => $registrationType->id,
            'user_id' => $participant->id,
            'activated' => true,
            'activated_at' => Carbon::now(),
        ]);

        $token = $registration->activation_code;

        // Perform task
        $this->actingAs($user)
            ->json('POST', '/registrations/'.Hashids::encode($registration->id).'/activate/'.$token);

        // Assertions
        $this->assertEquals(1, Registration::where(['activated' => true])->count(), 'Registration was not activated.');
        $this->assertNotNull(Registration::first()->activated_at, 'Activation timestamp was not stored.');
        $this->assertResponseStatus(Response::HTTP_FORBIDDEN);
        $this->seeJsonStructure([
            'error' => [
                'code', 'http_code', 'messages',
            ],
        ]);
    }

    public function testActivateRegistrationWithInvalidCode()
    {
        // Prepare data
        $user = factory(User::class)->create();
        $participant = factory(User::class)->create();

        $event = factory(Event::class)->create([
            'user_id' => $user->id,
        ]);

        $registrationType = factory(RegistrationType::class)->create([
            'event_id' => $event->id,
        ]);

        $registration = factory(Registration::class)->create([
            'event_id' => $event->id,
            'registration_type_id' => $registrationType->id,
            'user_id' => $participant->id,
        ]);

        $token = 'invalidcode';

        // Perform task
        $this->actingAs($user)
            ->json('POST', '/registrations/'.Hashids::encode($registration->id).'/activate/'.$token);

        // Assertions
        $this->assertResponseStatus(Response::HTTP_NOT_FOUND);
        $this->assertEquals(0, Registration::where(['activated' => true])->count(), 'Registration was activated unexpectedly.');
    }

    public function testResendTicketEmail()
    {
        // Prepare data
        $user = factory(User::class)->create();
        $participant = factory(User::class)->create();
        $event = factory(Event::class)->create([
            'user_id' => $user->id,
        ]);
        $registrationType = factory(RegistrationType::class)->create([
            'event_id' => $event->id,
        ]);
        $registration = factory(Registration::class)->create([
            'event_id' => $event->id,
            'registration_type_id' => $registrationType->id,
            'user_id' => $participant->id,
        ]);

        // Pretend emails
        Mail::fake();

        // Perform task
        $this->actingAs($user)
            ->json('POST', '/registrations/'.Hashids::encode($registration->id).'/resend');

        // Assertions
        $this->assertResponseOk();
        $this->seeJson([
            'success' => true,
        ]);

        // Assert email
        $this->assertTicketEmail($registration);
    }

    public function testResendTicketEmailOfActivatedRegistration()
    {
        // Prepare data
        $user = factory(User::class)->create();
        $participant = factory(User::class)->create();
        $event = factory(Event::class)->create([
            'user_id' => $user->id,
        ]);
        $registrationType = factory(RegistrationType::class)->create([
            'event_id' => $event->id,
        ]);
        $registration = factory(Registration::class)->create([
            'event_id' => $event->id,
            'registration_type_id' => $registrationType->id,
            'user_id' => $participant->id,
            'activated' => true,
        ]);

        // Pretend emails
        Mail::fake();

        // Perform task
        $this->actingAs($user)
            ->json('POST', '/registrations/'.Hashids::encode($registration->id).'/resend');

        // Assertions
        $this->assertResponseStatus(Response::HTTP_FORBIDDEN);
        $this->seeJsonStructure([
            'error' => [
                'code', 'http_code', 'messages',
            ],
        ]);

        // Assert email
        Mail::assertNotSent(TicketMail::class);
    }

    public function testDeleteRegistration()
    {
        // Prepare data
        $user = factory(User::class)->create();
        $participant = factory(User::class)->create();
        $event = factory(Event::class)->create([
            'user_id' => $user->id,
        ]);
        $registrationType = factory(RegistrationType::class)->create([
            'event_id' => $event->id,
        ]);
        $registration = factory(Registration::class)->create([
            'event_id' => $event->id,
            'registration_type_id' => $registrationType->id,
            'user_id' => $participant->id,
        ]);

        // Perform task
        $this->actingAs($user)
            ->json('DELETE', '/registrations/'.Hashids::encode($registration->id));

        // Assertions
        $this->assertResponseOk();
        $this->assertEquals(0, Registration::count(), 'Registration was not deleted from database');
        $this->seeJsonStructure([
            'success',
        ]);
    }

    private function assertTicketEmail($registration)
    {
        Mail::assertSent(TicketMail::class, function ($mail) use ($registration) {
            return $mail->registration->id === $registration->id &&
                   $mail->hasTo($registration->user->email);
        });
    }
}
