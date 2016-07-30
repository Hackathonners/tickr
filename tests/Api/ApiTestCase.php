<?php

abstract class ApiTestCase extends TestCase
{
    /**
     * The base URL to use while testing the application.
     *
     * @var string
     */
    protected $baseUrl = 'http://localhost/api/v1';
}
