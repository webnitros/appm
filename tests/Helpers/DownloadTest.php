<?php
/**
 * Created by Andrey Stepanenko.
 * User: webnitros
 * Date: 25.04.2022
 * Time: 11:53
 */

namespace Tests\Helpers;

use Tests\TestCase;

class DownloadTest extends TestCase
{
    public function testGetEnv()
    {
        self::assertEquals('testing', getenv('ENV'));
    }

}
