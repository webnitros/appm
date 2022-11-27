<?php
/**
 * Created by Andrey Stepanenko.
 * User: webnitros
 * Date: 19.10.2022
 * Time: 11:27
 */

namespace AppM;

use Symfony\Component\Routing\RouteCollection;

class Route extends RouteCollection
{
    /**
     * The route group attribute stack.
     *
     * @var array
     */
    protected $groupStack = [];
    /**
     * All of the verbs supported by the router.
     *
     * @var string[]
     */
    public static $verbs = ['GET', 'HEAD', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'];

    public function __construct()
    {
        $this->currentGroupPrefix = '';
    }

    /**
     * @param $methods
     * @param $uri
     * @param $action
     */
    public function addRoute($methods, $uri, $action)
    {
        $uri = $this->currentGroupPrefix . $uri;
        $Route = $this->createRoute($methods, $uri, $action);

        $this->add($uri, $Route);
    }


    /**
     *  Create a new route instance.
     * @param $methods
     * @param $uri
     * @param $action
     * @return \Symfony\Component\Routing\Route
     */
    protected function createRoute($methods, $uri, $action)
    {
        // If the route is routing to a controller we will parse the route action into
        // an acceptable array format before registering it and creating this route
        // instance itself. We need to build the Closure that will call this out.
        if ($this->actionReferencesController($action)) {
            $action = $this->convertToControllerAction($action);
        }

        if ($this->hasGroupStack()) {
            $events = $this->getGroupStack();
            $action[] = $events;
        }

        $route = $this->newRoute(
            $methods, $this->prefix($uri), $action
        );
        return $route;
    }


    /**
     * Get the current group stack for the router.
     *
     * @return array
     */
    public function getGroupStack()
    {
        return $this->groupStack;
    }

    /**
     * Determine if the action is routing to a controller.
     *
     * @param mixed $action
     * @return bool
     */
    protected function actionReferencesController($action)
    {
        if (!$action instanceof Closure) {
            return is_string($action) || (isset($action['uses']) && is_string($action['uses']));
        }

        return false;
    }

    /**
     * Add a controller based route action to the action array.
     *
     * @param array|string $action
     * @return array
     */
    protected function convertToControllerAction($action)
    {
        if (is_string($action)) {
            $action = ['uses' => $action];
        }

        // Here we'll merge any group "controller" and "uses" statements if necessary so that
        // the action has the proper clause for this property. Then, we can simply set the
        // name of this controller on the action plus return the action array for usage.
        if ($this->hasGroupStack()) {
            $action['uses'] = $this->prependGroupController($action['uses']);
            $action['uses'] = $this->prependGroupNamespace($action['uses']);
        }

        // Here we will set this controller name on the action array just so we always
        // have a copy of it for reference if we need it. This can be used while we
        // search for a controller name or do some other type of fetch operation.
        $action['controller'] = $action['uses'];

        return $action;
    }

    /**
     * Prepend the last group namespace onto the use clause.
     *
     * @param string $class
     * @return string
     */
    protected function prependGroupNamespace($class)
    {
        $group = end($this->groupStack);

        return isset($group['namespace']) && strpos($class, '\\') !== 0
            ? $group['namespace'] . '\\' . $class : $class;
    }

    /**
     * Prepend the last group controller onto the use clause.
     *
     * @param string $class
     * @return string
     */
    protected function prependGroupController($class)
    {
        $group = end($this->groupStack);
        if (!isset($group['controller'])) {
            return $class;
        }

        if (class_exists($class)) {
            return $class;
        }

        if (strpos($class, '@') !== false) {
            return $class;
        }

        return $group['controller'] . '@' . $class;
    }


    /**
     * Determine if the router currently has a group stack.
     *
     * @return bool
     */
    public function hasGroupStack()
    {
        return !empty($this->groupStack);
    }

    /**
     * @param $methods
     * @param $uri
     * @param $action
     * @return \Symfony\Component\Routing\Route
     */
    public function newRoute($methods, $uri, $action)
    {
        return (new \Symfony\Component\Routing\Route($uri, [
            '_controller' => $action,
            'methods' => $methods,
        ]));
    }

    /**
     * Register a new GET route with the router.
     * @param string $uri
     * @param null $action
     * @return \Symfony\Component\Routing\Route|void|null
     */
    public function get($uri, $action = null)
    {
        $this->addRoute(['GET', 'HEAD'], $uri, $action);
    }

    /**
     * Register a new POST route with the router.
     * @param $uri
     * @param null $action
     */
    public function post($uri, $action = null)
    {
        $this->addRoute('POST', $uri, $action);
    }

    /**
     * Register a new PUT route with the router.
     * @param $uri
     * @param null $action
     */
    public function put($uri, $action = null)
    {
        $this->addRoute('PUT', $uri, $action);
    }

    /**
     * Register a new PATCH route with the router.
     * @param $uri
     * @param null $action
     */
    public function patch($uri, $action = null)
    {
        $this->addRoute('PATCH', $uri, $action);
    }

    /**
     * Register a new DELETE route with the router.
     * @param $uri
     * @param null $action
     */
    public function delete($uri, $action = null)
    {
        $this->addRoute('DELETE', $uri, $action);
    }

    /**
     * Register a new OPTIONS route with the router.
     * @param $uri
     * @param null $action
     */
    public function options($uri, $action = null)
    {
        $this->addRoute('OPTIONS', $uri, $action);
    }

    /**
     * Register a new route responding to all verbs.
     * @param $uri
     * @param null $action
     */
    public function any($uri, $action = null)
    {
        $this->addRoute(self::$verbs, $uri, $action);
    }


    /** @var string */
    protected $currentGroupPrefix;

    /**
     * @param array $attributes
     * @param $callback
     */
    public function group(array $attributes, $callback)
    {
        $this->groupStack = $attributes;
        $callback($this);
    }

    /**
     * Prefix the given URI with the last prefix.
     *
     * @param string $uri
     * @return string
     */
    protected function prefix($uri)
    {
        return trim(trim($this->getLastGroupPrefix(), '/') . '/' . trim($uri, '/'), '/') ?: '/';
    }

    /**
     * @param $uri
     * @return $this
     */
    public function prefixStack($uri)
    {
        $this->currentGroupPrefix = $this->prefix($uri);
        return $this;
    }


    /**
     * Get the prefix from the last group on the stack.
     *
     * @return string
     */
    public function getLastGroupPrefix()
    {
        if ($this->hasGroupStack()) {
            $last = end($this->groupStack);
            return $last['prefix'] ?? '';
        }
        return '';
    }


    /**
     * Get a route instance by its name.
     *
     * @param string $name
     * @return \Illuminate\Routing\Route|null
     */
    public function getByName($name)
    {
        $all = $this->all();

        $attr = [];
        foreach ($all as $key => $item) {
            $newKey = str_ireplace('/', '.', $key);
            if (strripos($newKey, '{') !== false) {
                $newKey = explode('.{', $newKey);
                $newKey = $newKey[0];
            }
            $attr[$newKey] = $key;
        }

        if (array_key_exists($name, $attr)) {
            $route = $attr[$name];
            if (array_key_exists($route, $all)) {
                return $all[$route];
            }
        }

        return null;
    }


}
