<?php

namespace AppM\Http\Controllers\Page;

use AppM\Http\Controllers\Controller;
use AppM\Http\Middleware\StartSession;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MainController extends Controller
{
    protected $middleware = [
        StartSession::class,
    ];

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function index(Request $request)
    {
        // Валидация запроса
        $this->validatorResponse($request, [
            'text' => 'required|min:3|max:255'
        ]);

        /*$this->validate($request, [
            'password' => 'required|confirmed|min:6',
        ]);*/
        return new JsonResponse([
            'text' => 'text',
        ], 201);
    }
}
