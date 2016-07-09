<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Karina\User;
use App\Karina\Event;

class EventsTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCreateEventByOrganizer()
    {
        // Prepare data
        $user = factory(User::class)->create();
        $event = factory(Event::class)->make();

        // Perform task
        $this->actingAs($user)
             ->post('/events', $event->toArray());

        // Assertions
        $this->assertResponseOk();
        $this->assertEquals(1, Event::count(), 'Event was not stored in database.');
        $this->seeJson(Event::first()->toArray());
    }
}
