<?php

use App\Karina\User;
use App\Transformers\UserTransformer;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Symfony\Component\HttpFoundation\Response;

class UsersTest extends ApiTestCase
{
    use DatabaseTransactions;

    public function testGetUserByEmail()
    {

        // Prepare data
        $user = factory(User::class)->create();

        // Perform task
        $this->actingAs($user)
            ->json('GET', '/users/'.$user->email);

        // Assertions
        $this->assertResponseOk();
        $this->seeJson(Fractal::item($user, new UserTransformer)->toArray());
    }

    public function testGetAbsentUserByEmail()
    {

        // Prepare data
        $user = factory(User::class)->create();
        $email = 'lmer@hast123.dev';

        // Perform task
        $this->actingAs($user)
            ->json('GET', '/users/'.$email);

        // Assertions
        $this->assertResponseStatus(Response::HTTP_NOT_FOUND);
        $this->assertEquals(0, User::where(['email' => $email])->count(), 'User exists unexpectedly.');
    }

    public function testGetUserById()
    {

        // Prepare data
        $user = factory(User::class)->create();

        // Perform task
        $this->actingAs($user)
            ->json('GET', '/users/'.$user->id);

        // Assertions
        $this->assertResponseOk();
        $this->seeJson(Fractal::item($user, new UserTransformer)->toArray());
    }
}
