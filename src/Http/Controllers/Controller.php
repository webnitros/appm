<?php

namespace AppM\Http\Controllers;

use AppM\Interfaces\Middleware;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Symfony\Contracts\EventDispatcher\Event;

abstract class Controller extends BaseController
{
    protected $middleware;

    public function __construct()
    {
        $this->loadMiddlewareKernelController();
    }

    /**
     * Собстрвенные прокладки для загрузки
     */
    private function loadMiddlewareKernelController()
    {
        if ($this->middleware) {
            $dispatcher = app('dispatcher');
            $controller = $this;
            $dispatcher->addListener('kernel.controller', function (Event $event) use ($controller) {
                $middlewares = $this->middleware;
                $reguest = $event->getRequest();
                foreach ($middlewares as $middleware) {

                    // проверяем чтобы небыло остановки
                    if (!$event->isPropagationStopped()) {
                        if (is_callable($middleware)) {
                            $middleware($reguest);
                        } else {
                            $Middleware = new $middleware();
                            if ($Middleware instanceof Middleware) {
                                $Middleware->handle($controller, $reguest, $event);
                            }
                        }
                    }
                }


            });
        }
    }

    /**
     * Выбросит исключение с ошибкой
     * @param Request $request
     * @param array $rules
     * @param array $messages
     * @param array $customAttributes
     * @return bool|JsonResponse
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    protected function validatorResponse(Request $request, array $rules, array $messages = [], array $customAttributes = [])
    {
        try {
            $this->validator($request->all(), $rules, $messages, $customAttributes)->validate();
        } catch (\Illuminate\Validation\ValidationException $e) {
            $arrError = $e->errors();
            foreach ($arrError as $key => $value) {
                $arrImplode[$key] = implode(', ', $arrError[$key]);
            }
            $message = implode(', ', $arrImplode);
            return new JsonResponse([
                'reason' => $message,
                'data' => $arrImplode
            ], $e->status);
        }
        return true;
    }

    /**
     * @param array $data
     * @param array $rules
     * @return mixed|\Illuminate\Validation\Validator
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    protected function validator(array $data, array $rules, array $messages = [], array $customAttributes = [])
    {
        return app('Validator')->make($data, $rules, $messages, $customAttributes);
    }

    /**
     * Validate the given request with the given rules.
     *
     * @param \Illuminate\Http\Request $request
     * @param array $rules
     * @param array $messages
     * @param array $customAttributes
     * @return array
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function validate(Request $request, array $rules, array $messages = [], array $customAttributes = [])
    {
        return $this->validator($request->all(), $rules, $messages, $customAttributes)->validate();
    }

    /**
     * Get the post register / login redirect path.
     *
     * @return string
     */
    public function redirectPath()
    {
        if (method_exists($this, 'redirectTo')) {
            return $this->redirectTo();
        }

        return property_exists($this, 'redirectTo') ? $this->redirectTo : '/login';
    }

}
