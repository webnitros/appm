<?php
/**
 * Created by Andrey Stepanenko.
 * User: webnitros
 * Date: 24.03.2021
 * Time: 22:49
 */

namespace Tests;

use AppM\Foundation\MakesHttpRequests;
use Mockery\Adapter\Phpunit\MockeryTestCase;

abstract class TestCase extends MockeryTestCase
{
    use MakesHttpRequests;

    /**
     * The Illuminate application instance.
     *
     * @var \Illuminate\Container\Container
     */
    protected $app;

    protected function setUp(): void
    {
        $this->app = app();
        parent::setUp();
    }

}
