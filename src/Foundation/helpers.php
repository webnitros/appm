<?php
/**
 * Created by Andrey Stepanenko.
 * User: webnitros
 * Date: 06.11.2022
 * Time: 14:59
 */


if (!function_exists('app')) {

    /**
     * Get the available container instance.
     *
     * @param string|null $abstract
     * @param array $parameters
     * @return mixed|\AppM\Foundation\Application
     */
    function app($abstract = null, array $parameters = [])
    {
        if (is_null($abstract)) {
            return \AppM\Foundation\Application::getInstance();
        }
        return \AppM\Foundation\Application::getInstance()->make($abstract, $parameters);
    }
}
