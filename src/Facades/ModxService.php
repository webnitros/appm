<?php
/**
 * Created by Andrey Stepanenko.
 * User: webnitros
 * Date: 19.11.2022
 * Time: 13:26
 */

namespace AppM\Facades;

use Illuminate\Support\Facades\Facade;
use xPDOQuery;

/**
 * @method static self object($criteria)
 * @method static mixed runProcessor($action = '',$scriptProperties = array(),$options = array())
 * @method static mixed getOption($key, $options = null, $default = null, $skipEmpty = false)
 * @method static object|null getObject($className, $criteria= null, $cacheFlag= true)
 * @method static object|null newObject($className, $fields= array ())
 * @method static array|null getCollection($className, $criteria= null, $cacheFlag= true)
 * @method static xPDOQuery newQuery($class, $criteria= null, $cacheFlag= true)
 * @see modX
 */
class ModxService extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'modx';
    }
}
