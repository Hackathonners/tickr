<?php

use App\Karina\GuestList;
use App\Karina\User;
use App\Karina\Guest;
use App\Transformers\GuestListTransformer;
use App\Transformers\GuestTransformer;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Symfony\Component\HttpFoundation\Response;

class GuestTest extends ApiTestCase
{
    use DatabaseTransactions;

    public function testGuestsSearchByName()
    {
        // Prepare data
        // Guest List of my user
        $user = factory(User::class)->create();

        $guests = [];
        $guests[0] = factory(Guest::class)->make([
            'name' => 'A very çomplicatéd nãmè'
        ]);
        $guests[1] = factory(Guest::class)->make([
            'name' => 'Chuck Norris'
        ]);

        $guestList = factory(GuestList::class)->create([
            'user_id' => $user->id,
        ]);
        $guestList->guests()->saveMany($guests);

        // Guest List of other user
        $user2 = factory(User::class)->create();

        $guests2 = factory(Guest::class)->make([
            'name' => 'A fancy name',
        ]);

        $guestList2 = factory(GuestList::class)->create([
            'user_id' => $user2->id,
        ]);
        $guestList2->guests()->save($guests2);

        // Perform task
        $this->actingAs($user)
            ->json('GET', '/guests?search=nam');

        // Assertions
        $this->assertResponseOk();
        // Only see match associated to my guestlist
        $this->seeJsonEquals(Fractal::collection([$guests[0]], new GuestTransformer)->toArray());
    }
}
