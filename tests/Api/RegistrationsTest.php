<?php

use App\Karina\Event;
use App\Karina\Registration;
use App\Karina\RegistrationType;
use App\Karina\User;
use App\Transformers\RegistrationTransformer;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RegistrationsTest extends ApiTestCase
{
    use DatabaseTransactions;

    public function testCreateRegistration()
    {
        // Prepare data
        $user = factory(User::class)->create();

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
        ];

        // Assert Emails
        $this->assertTicketEmail($event, $data);

        // Perform task
        $this->actingAs($user)
            ->json('POST', '/events/'.$event->id.'/registrations', $data);

        // Assertions
        $this->assertResponseOk();
        $this->assertEquals(2, User::count(), 'Participant was not stored in database');
        $this->assertEquals(1, Registration::count(), 'Registration was not stored in database');
        $this->seeJson(Fractal::item(Registration::first(), new RegistrationTransformer)->toArray());
        $this->seeJson([
            'fined' => false,
            'activated' => false,
        ]);
    }

    public function testCreateRegistrationForExistingUser()
    {
        // Prepare data
        $user = factory(User::class)->create();
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

        // Assert Emails
        $this->assertTicketEmail($event, $data);

        // Perform task
        $this->actingAs($user)
            ->json('POST', '/events/'.$event->id.'/registrations', $data);

        // Assertions
        $this->assertResponseOk();
        $this->assertEquals(2, User::count(), 'Users are not strict to owner and participant.');
        $this->assertEquals(1, Registration::count(), 'Registration was not stored in database');
        $this->seeJson(Fractal::item(Registration::first(), new RegistrationTransformer)->toArray());
        $this->seeJson([
            'fined' => true,
            'activated' => false,
        ]);
    }

    private function assertTicketEmail($event, $data)
    {
        Mail::shouldReceive('queue')->once()
            ->with('emails.ticket', Mockery::on(function ($data) {
                $this->assertArrayHasKey('registration', $data);

                return true;
            }), Mockery::on(function ($closure) use ($data, $event) {
                $message = Mockery::mock(\Illuminate\Mailer\Message::class);
                $message->shouldReceive('from')
                    ->with(config('mail.from.address'), config('mail.from.name'))
                    ->andReturn(Mockery::self())
                    ->shouldReceive('to')
                    ->with($data['email'], $data['name'])
                    ->andReturn(Mockery::self())
                    ->shouldReceive('subject')
                    ->with('Ticket for '.$event->title)
                    ->andReturn(Mockery::self());
                $closure($message);

                return true;
            }));
    }
}
