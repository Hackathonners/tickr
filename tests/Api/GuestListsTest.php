<?php

use App\Karina\GuestList;
use App\Karina\User;
use App\Transformers\GuestListTransformer;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class GuestListsTest extends ApiTestCase
{
    use DatabaseTransactions;

    public function testCreateGuestList()
    {
        // Prepare data
        $user = factory(User::class)->create();
        $guestUser = factory(User::class)->create([
            'email' => 'a@a.com'
        ]);

        $guest = factory(User::class)->make([
            'email' => 'a@b.com'
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
            'name' => 'Dummy',
            'description' => 'Things',
            'email' => 'a@a.com', // See user that already existed before guestlist.
            'email' => 'a@b.com', // See user that is new.
        ]);
    }
}
