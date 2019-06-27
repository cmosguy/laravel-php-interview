<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Mockery;
use Mockery\MockInterface;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * @param $instance
     * @return MockInterface
     */
    public function newMock($instance): MockInterface
    {
        $mock = Mockery::mock($instance);
        app()->instance($instance, $mock);

        return $mock;
    }
}
