<?php
/**
 * Created by Andrey Stepanenko.
 * User: webnitros
 * Date: 19.11.2022
 * Time: 13:26
 */

namespace AppM\Facades;

use Illuminate\Support\Facades\Facade;
use modSystemEvent;

/**
 * @method static void loadHandlerEvent(modSystemEvent $event, $scriptProperties = array())
 * @method static string getChunk($name = '', array $properties = array(), $fastMode = false)
 * @see Auth
 */
class PdoToolsService extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'pdotools.service';
    }

}
