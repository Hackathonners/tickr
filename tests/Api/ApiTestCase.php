<?php

use Laravel\BrowserKitTesting\WithoutMiddleware;
use Laravel\BrowserKitTesting\DatabaseTransactions;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;

abstract class ApiTestCase extends BrowserKitTestCase
{
    use WithoutMiddleware;
    use DatabaseTransactions;

    /**
     * The base URL to use while testing the application.
     *
     * @var string
     */
    protected $baseUrl = 'http://localhost/api/v1';

    /**
     * Set the currently logged in user for the application.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  string|null  $driver
     * @return $this
     */
    public function actingAs(UserContract $user, $driver = null)
    {
        $this->be($user, 'api');

        return $this;
    }
}
