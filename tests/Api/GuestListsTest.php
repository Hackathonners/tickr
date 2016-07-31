<?php

use App\Karina\GuestList;
use App\Karina\User;
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
        $guestUser = factory(User::class)->create([
            'email' => 'a@a.com',
        ]);

        $guest = factory(User::class)->make([
            'email' => 'a@b.com',
        ]);

        $data = [
            'name' => 'Dummy',
            'description' => 'Things',
            'guest' => [
                $guestUser->toArray(),
                $guest->toArray(),
            ],
        ];

        // Perform task
        $this->actingAs($user)
            ->json('POST', '/guestlists', $data);

        // Assertions
        $this->assertResponseOk();
        $this->assertEquals(3, User::count());
        $this->assertEquals(1, GuestList::count());
        $this->seeJson(Fractal::item(GuestList::first(), new GuestListTransformer)->toArray());
        $this->seeJson([
            'name' => $data['name'],
            'description' => $data['description'],
            'email' => $data['guest'][0]['email'], // See user that already existed before guestlist.
            'email' => $data['guest'][1]['email'], // See user that is new.
        ]);
    }

    public function testShowEvent()
    {
        // Prepare data
        $user = factory(User::class)->create();
        $guests = factory(User::class, 3)->create();
        $guestList = factory(GuestList::class)->create([
            'user_id' => $user->id,
        ]);
        $guestList->users()->sync($guests);

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
        $guests = factory(User::class, 3)->create();
        $guestList = factory(GuestList::class)->create([
            'user_id' => $user->id,
            'name' => 'Old title',
        ]);
        $guestList->users()->sync($guests);

        $newGuest = factory(User::class)->make([
            'email' => 'a@b.com',
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
        $this->assertEquals(1, GuestList::first()->users()->count(), 'There are more guests than expected.');
        $this->seeJson(Fractal::item(GuestList::first(), new GuestListTransformer)->toArray());
        $this->seeJson([
            'name' => $data['name'],
            'description' => $data['description'],
            'email' => $data['guest'][0]['email'], // See user that is new.
        ]);
        $this->dontSeeJson([
            'email' => $guests[0]['email'], // Do not see old users.
            'email' => $guests[1]['email'],
            'email' => $guests[2]['email'],
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
        $guests = factory(User::class, 3)->create();
        $guestList = factory(GuestList::class)->create([
            'user_id' => $dummy->id,
        ]);
        $guestList->users()->sync($guests);

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
