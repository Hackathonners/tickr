<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;

abstract class ApiTestCase extends TestCase
{
    use WithoutMiddleware;

    /**
     * The base URL to use while testing the application.
     *
     * @var string
     */
    protected $baseUrl = 'http://localhost/api/v1';
}
