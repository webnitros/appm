<?php
/**
 * Created by Andrey Stepanenko.
 * User: webnitros
 * Date: 06.11.2022
 * Time: 17:26
 */

namespace AppM;

use AppM\Helpers\UrlGenerator;
use AppM\Http\Kernel;
use Illuminate\Container\Container;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Facade;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\HttpKernel\EventListener\RouterListener;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;

class Application
{

    /**
     * @return \AppM\Foundation\Application|mixed
     */
    public static function create($router)
    {
        $app = app();

        // events
        $app->singleton('dispatcher', EventDispatcher::class);

        // events
        $app->singleton('Validator', ValidatorFactory::class);


        // routes
        if (is_callable($router)) {
            $app->singleton('router', Route::class);
            // Роутеры регистрируем после создания
            $app->resolving('router', $router);
        }


        // events
        $app->singleton('url', function () {
            return (new UrlGenerator(app('router'), Request::createFromGlobals()));
        });


        // Kernel
        $app->singleton(Kernel::class, function (Container $container) {

            // регистрация событи
            /* @var EventDispatcher $dispatcher */
            $dispatcher = $container->make('dispatcher');

            #$dispatcher = new EventDispatcher();

            // регистраиця роутеров
            $routes = $container->make('router');

            // какие то проблемы с роутерами решает
            $matcher = new UrlMatcher($routes, new RequestContext());
            #$dispatcher = new EventDispatcher();

            // add event Routers
            $dispatcher->addSubscriber(new RouterListener($matcher, new RequestStack()));

            // end
            $controllerResolver = new ControllerResolver();

            $argumentResolver = new ArgumentResolver();

            return new Kernel($dispatcher, $controllerResolver, new RequestStack(), $argumentResolver);
            #return new HttpKernel($dispatcher, $controllerResolver, new RequestStack(), $argumentResolver);
        });
        return $app;
    }

    public function regFacade($app)
    {
        Facade::clearResolvedInstances();
        Facade::setFacadeApplication($app);
    }
}
