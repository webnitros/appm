<?php
/**
 * Created by Andrey Stepanenko.
 * User: webnitros
 * Date: 27.11.2022
 * Time: 10:43
 */

namespace AppM\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see Route
 */
class Router extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'router';
    }

}
