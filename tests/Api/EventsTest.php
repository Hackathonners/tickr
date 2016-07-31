<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Symfony\Component\HttpFoundation\Response;
use App\Karina\User;
use App\Karina\Event;
use App\Karina\RegistrationType;
use App\Transformers\EventTransformer;

class EventsTest extends ApiTestCase
{
    use DatabaseTransactions;

    public function testGetUserEvents()
    {
        // Prepare data
        $user = factory(User::class)->create();

        $startAt = (new DateTime())->modify('+1 day');
        $endAt = (new DateTime())->modify('+5 day');
        factory(Event::class, 20)->create([
            'user_id' => $user->id,
            'title' => 'Event name',
            'description' => 'Event description',
            'place' => 'Place',
            'start_at' => $startAt->format('Y-m-d H:i:s'),
            'end_at' => $endAt->format('Y-m-d H:i:s'),
        ])->each(function ($event) {
            factory(RegistrationType::class, 2)->create([
                'event_id' => $event->id,
            ]);
        });
        $events = $user->events()->paginate();

        // Perform task
        $this->actingAs($user)
            ->json('GET', '/events');

        // Assertions
        $this->assertResponseOk();
        $this->seeJsonEquals(Fractal::collection($events, new EventTransformer)->toArray());
    }

    public function testCreateEvent()
    {
        // Prepare data
        $user = factory(User::class)->create();

        $startAt = (new DateTime())->modify('+1 day');
        $endAt = (new DateTime())->modify('+5 day');
        $event = factory(Event::class)->make([
                'title' => 'Event name',
                'description' => 'Event description',
                'place' => 'Place',
                'start_at' => $startAt->format('Y-m-d H:i:s'),
                'end_at' => $endAt->format('Y-m-d H:i:s'),
                'registration' => factory(RegistrationType::class, 3)->make()->toArray(),
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

        $startAt = (new DateTime())->modify('+1 day');
        $endAt = (new DateTime());
        $event = factory(Event::class)->make([
                'start_at' => $startAt->format('Y-m-d H:i:s'),
                'end_at' => $endAt->format('Y-m-d H:i:s'),
            ]);

        // Perform task
        $this->actingAs($user)
             ->json('POST', '/events', $event->toArray());

        // Assertions
        $this->assertResponseStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $this->assertEquals(0, Event::count(), 'Event was stored in database.');
        $this->seeJsonStructure([
                'end_at',
            ]);
    }

    public function testCreateEventWithNoRegistrationTypes()
    {
        // Prepare data
        $user = factory(User::class)->create();

        $startAt = (new DateTime())->modify('+1 day');
        $endAt = (new DateTime())->modify('+2 day');
        $event = factory(Event::class)->make([
                'start_at' => $startAt->format('Y-m-d H:i:s'),
                'end_at' => $endAt->format('Y-m-d H:i:s'),
                'registration' => [],
            ]);

        // Perform task
        $this->actingAs($user)
             ->json('POST', '/events', $event->toArray());

        // Assertions
        $this->assertResponseStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $this->assertEquals(0, Event::count(), 'Event was stored in database.');
        $this->seeJsonStructure([
                'registration.0',
            ]);
    }

    public function testUpdateEvent()
    {
        // Prepare data
        $user = factory(User::class)->create();

        $startAt = (new DateTime())->modify('+5 day');
        $endAt = (new DateTime())->modify('+8 day');
        $event = factory(Event::class, 1)->create([
            'user_id' => $user->id,
            'title' => 'Event name',
            'description' => 'Event description',
            'place' => 'Place',
            'start_at' => $startAt->format('Y-m-d H:i:s'),
            'end_at' => $endAt->format('Y-m-d H:i:s'),
        ]);

        // Perform task
        $newStartAt = (new DateTime())->modify('+2 day');
        $data = [
            'title' => 'New Title',
            'start_at' => $newStartAt->format('Y-m-d H:i:s'),
        ];
        $this->actingAs($user)
            ->json('PUT', '/events/'.$event->id, $data);

        // Assertions
        $this->assertResponseOk();
        $this->seeJson($data);
    }

    public function testShowEvent()
    {
        // Prepare data
        $user = factory(User::class)->create();

        $startAt = (new DateTime())->modify('+5 day');
        $endAt = (new DateTime())->modify('+8 day');
        $event = factory(Event::class, 1)->create([
            'user_id' => $user->id,
            'title' => 'Event name',
            'description' => 'Event description',
            'place' => 'Place',
            'start_at' => $startAt->format('Y-m-d H:i:s'),
            'end_at' => $endAt->format('Y-m-d H:i:s'),
        ]);

        // Perform task
        $this->actingAs($user)
            ->json('GET', '/events/'.$event->id);

        // Assertions
        $this->assertResponseOk();
        $this->seeJson(Fractal::item(Event::first(), new EventTransformer)->toArray());
    }

    public function testUpdateAlreadyStartedEvent()
    {
        // Prepare data
        $user = factory(User::class)->create();

        $startAt = (new DateTime())->modify('-1 year');
        $endAt = (new DateTime())->modify('+8 day');
        $event = factory(Event::class, 1)->create([
            'user_id' => $user->id,
            'title' => 'Event name',
            'description' => 'Event description',
            'place' => 'Place',
            'start_at' => $startAt->format('Y-m-d H:i:s'),
            'end_at' => $endAt->format('Y-m-d H:i:s'),
        ]);

        // Perform task
        $newStartAt = (new DateTime())->modify('+2 day');
        $data = [
            'title' => 'New Title',
            'start_at' => $newStartAt->format('Y-m-d H:i:s'),
        ];
        $this->actingAs($user)
            ->json('PUT', '/events/'.$event->id, $data);

        // Assertions
        $this->assertResponseStatus(Response::HTTP_FORBIDDEN);
        $this->seeJsonStructure([
            'error' => [
                'code', 'http_code', 'message',
            ],
        ]);
    }

    public function testDeleteEvent()
    {
        // Prepare data
        $user = factory(User::class)->create();

        $startAt = (new DateTime())->modify('+1 day');
        $endAt = (new DateTime())->modify('+5 day');
        $event = factory(Event::class, 1)->create([
            'user_id' => $user->id,
            'title' => 'Event name',
            'description' => 'Event description',
            'place' => 'Place',
            'start_at' => $startAt->format('Y-m-d H:i:s'),
            'end_at' => $endAt->format('Y-m-d H:i:s'),
        ]);

        // Perform task
        $this->actingAs($user)
            ->json('DELETE', '/events/'.$event->id);

        // Assertions
        $this->assertResponseOk();
        $this->assertEquals(0, Event::count(), 'Event was not soft-deleted from database.');
        $this->assertEquals(1, Event::withTrashed()->count(), 'Event was not even created in database.');
        $this->seeJson([
            'success' => true,
        ]);
        $this->seeJsonStructure([
            'success',
            'message',
        ]);
    }

    public function testUnauthorizedActions()
    {
        // Prepare data
        $user = factory(User::class)->create();
        $dummy = factory(User::class)->create();

        $startAt = (new DateTime())->modify('+1 day');
        $endAt = (new DateTime())->modify('+5 day');
        $event = factory(Event::class, 1)->create([
            'user_id' => $dummy->id,
            'title' => 'Event name',
            'description' => 'Event description',
            'place' => 'Place',
            'start_at' => $startAt->format('Y-m-d H:i:s'),
            'end_at' => $endAt->format('Y-m-d H:i:s'),
        ]);

        ////
        // DELETE
        ////

        // Perform task
        $this->actingAs($user)
            ->json('DELETE', '/events/'.$event->id);

        // Assertions
        $this->assertResponseStatus(Response::HTTP_FORBIDDEN);
        $this->assertEquals(1, Event::count(), 'Unauthorized delete of event was performed.');
        $this->assertEquals(1, Event::withTrashed()->count(), 'Event was not even created in database.');

        ////
        // SHOW
        ////

        // Perform task
        $this->actingAs($user)
            ->json('GET', '/events/'.$event->id);

        // Assertions
        $this->assertResponseStatus(Response::HTTP_FORBIDDEN);

        ////
        // UPDATE
        ////

        // Perform task
        $this->actingAs($user)
            ->json('PATCH', '/events/'.$event->id, [
                'title' => 'New name',
            ]);

        // Assertions
        $this->assertResponseStatus(Response::HTTP_FORBIDDEN);
    }
}
