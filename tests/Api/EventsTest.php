<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use Symfony\Component\HttpFoundation\Response;

use App\Karina\User;
use App\Karina\Event;
use App\Karina\RegistrationType;

use App\Transformers\EventTransformer;

class EventsTest extends TestCase
{
    use DatabaseTransactions;

    public function testCreateEvent()
    {
        // Prepare data
        $user = factory(User::class)->create();

        $start_at = (new DateTime())->modify('+1 day');
        $end_at = (new DateTime())->modify('+5 day');
        $event = factory(Event::class)->make([
                'title' => 'Event name',
                'description' => 'Event description',
                'place' => 'Place',
                'start_at' => $start_at->format('Y-m-d H:i:s'),
                'end_at' => $end_at->format('Y-m-d H:i:s'),
                'registration' => factory(RegistrationType::class, 3)->make()->toArray()
            ]);

        // Perform task
        $this->actingAs($user)
             ->json('POST', '/events', $event->toArray());

        // Assertions
        $this->assertResponseOk();
        $this->assertEquals(1, Event::count(), 'Event was not stored in database.');
        $this->assertEquals(3, RegistrationType::count(), 'Registration types were not stored in database.');
        $this->seeJsonEquals(Fractal::item(Event::first(), new EventTransformer)->toArray());
    }

    public function testCreateEventWithInvalidTimePeriod()
    {
        // Prepare data
        $user = factory(User::class)->create();

        $start_at = (new DateTime())->modify('+1 day');
        $end_at = (new DateTime());
        $event = factory(Event::class)->make([
                'start_at' => $start_at->format('Y-m-d H:i:s'),
                'end_at' => $end_at->format('Y-m-d H:i:s'),
            ]);

        // Perform task
        $this->actingAs($user)
             ->json('POST', '/events', $event->toArray());

        // Assertions
        $this->assertResponseStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $this->assertEquals(0, Event::count(), 'Event was stored in database.');
        $this->seeJsonStructure([
                'end_at'
            ]);
    }

    public function testCreateEventWithNoRegistrationTypes()
    {
        // Prepare data
        $user = factory(User::class)->create();

        $start_at = (new DateTime())->modify('+1 day');
        $end_at = (new DateTime())->modify('+2 day');
        $event = factory(Event::class)->make([
                'start_at' => $start_at->format('Y-m-d H:i:s'),
                'end_at' => $end_at->format('Y-m-d H:i:s'),
                'registration' => [],
            ]);

        // Perform task
        $this->actingAs($user)
             ->json('POST', '/events', $event->toArray());

        // Assertions
        $this->assertResponseStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $this->assertEquals(0, Event::count(), 'Event was stored in database.');
        $this->seeJsonStructure([
                'registration.0'
            ]);
    }
}
