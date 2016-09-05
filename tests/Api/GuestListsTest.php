<?php

use App\Karina\GuestList;
use App\Karina\User;
use App\Karina\Guest;
use App\Transformers\GuestListTransformer;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Symfony\Component\HttpFoundation\Response;

class GuestListsTest extends ApiTestCase
{
    use DatabaseTransactions;

    public function testCreateGuestList()
    {
        // Prepare data
        $user = factory(User::class)->create();
        $guests = factory(Guest::class, 2)->make();

        $data = [
            'name' => 'Dummy',
            'description' => 'Things',
            'guest' => $guests->toArray(),
        ];

        // Perform task
        $this->actingAs($user)
            ->json('POST', '/guestlists', $data);

        // Assertions
        $this->assertResponseOk();
        $this->assertEquals(2, Guest::count());
        $this->assertEquals(1, GuestList::count());
        $this->seeJson(Fractal::item(GuestList::first(), new GuestListTransformer)->toArray());
        $this->seeJson([
            'name' => $data['name'],
            'description' => $data['description'],
            'name' => $data['guest'][0]['name'],
            'name' => $data['guest'][1]['name'],
            'notes' => $data['guest'][1]['notes'],
        ]);
    }

    public function testShowGuestList()
    {
        // Prepare data
        $user = factory(User::class)->create();
        $guestList = factory(GuestList::class)->create([
            'user_id' => $user->id,
        ]);
        $guests = factory(Guest::class, 2)->make();
        $guestList->guests()->saveMany($guests);

        // Perform task
        $this->actingAs($user)
            ->json('GET', '/guestlists/'.$guestList->id);

        // Assertions
        $this->assertResponseOk();
        $this->seeJson(Fractal::item(GuestList::first(), new GuestListTransformer)->toArray());
    }

    public function testUpdateGuestList()
    {
        // Prepare data
        $user = factory(User::class)->create();
        $guestList = factory(GuestList::class)->create([
            'user_id' => $user->id,
            'name' => 'Old title',
        ]);
        $guests = factory(Guest::class, 3)->make();
        $guestList->guests()->saveMany($guests);

        $newGuest = factory(User::class)->make([
            'name' => 'New Dummy Name',
            'notes' => 'New Dummy Notes',
        ]);

        $data = [
            'name' => 'Dummy XPTO',
            'description' => 'Things XPTO',
            'guest' => [
                $newGuest->toArray(),
            ],
        ];

        // Perform task
        $this->actingAs($user)
            ->json('PUT', '/guestlists/'.$guestList->id, $data);

        // Assertions
        $this->assertResponseOk();
        $this->assertEquals(1, GuestList::first()->guests()->count(), 'There are more guests than expected.');
        $this->seeJson(Fractal::item(GuestList::first(), new GuestListTransformer)->toArray());
        $this->seeJson([
            'name' => $data['name'],
            'description' => $data['description'],
            'name' => $data['guest'][0]['name'], // See guest that is new.
            'notes' => $data['guest'][0]['notes'], // See guest that is new.
        ]);
        $this->dontSeeJson([
            'name' => $guests[0]['name'], // Do not see old users.
            'name' => $guests[1]['name'],
            'name' => $guests[2]['name'],
        ]);
    }

    public function testDeleteGuestList()
    {
        // Prepare data
        $user = factory(User::class)->create();

        $guestList = factory(GuestList::class)->make();
        $guestList->user()->associate($user);
        $guestList->save();

        // Perform task
        $this->actingAs($user)
            ->json('DELETE', '/guestlists/'.$guestList->id);

        // Assertions
        $this->assertResponseOk();
        $this->assertEquals(0, GuestList::count(), 'Guest list was not soft-deleted from database.');
        $this->assertEquals(1, GuestList::withTrashed()->count(), 'Guest list was not even created in database.');
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
        $guestList = factory(GuestList::class)->create([
            'user_id' => $dummy->id,
        ]);
        $guests = factory(Guest::class, 3)->make();
        $guestList->guests()->saveMany($guests);

        ////
        // DELETE
        ////

        // Perform task
        $this->actingAs($user)
            ->json('DELETE', '/guestlists/'.$guestList->id);

        // Assertions
        $this->assertResponseStatus(Response::HTTP_FORBIDDEN);
        $this->assertEquals(1, GuestList::count(), 'Unauthorized delete of guest list was performed.');
        $this->assertEquals(1, GuestList::withTrashed()->count(), 'Guest list was not even created in database.');

        ////
        // SHOW
        ////

        // Perform task
        $this->actingAs($user)
            ->json('GET', '/guestlists/'.$guestList->id);

        // Assertions
        $this->assertResponseStatus(Response::HTTP_FORBIDDEN);

        ////
        // UPDATE
        ////

        // Perform task
        $this->actingAs($user)
            ->json('PATCH', '/guestlists/'.$guestList->id, [
                'name' => 'New',
            ]);

        // Assertions
        $this->assertResponseStatus(Response::HTTP_FORBIDDEN);
    }
}
