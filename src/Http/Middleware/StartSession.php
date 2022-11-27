<?php
/**
 * Запуск сессии для авторизации
 */

namespace AppM\Http\Middleware;

use AppM\Http\Controllers\Controller;
use AppM\Interfaces\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Event\ControllerEvent as Event;

class StartSession implements Middleware
{
    public function handle(Controller $controller, Request $request, Event $event): void
    {

    }
}
