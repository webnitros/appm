<?php
/**
 * Created by Andrey Stepanenko.
 * User: webnitros
 * Date: 07.11.2022
 * Time: 07:26
 */

namespace AppM\Interfaces;


use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Event\ControllerEvent as Event;

interface ControllerInterface
{
    public function handle(Controller $controller, Request $request, Event $event): void;
}
