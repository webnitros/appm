<?php
/**
 * Created by Andrey Stepanenko.
 * User: webnitros
 * Date: 07.11.2022
 * Time: 07:15
 */

namespace AppM\Http;

use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernel as HttpKernel;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class Kernel extends HttpKernel
{
    public function handle(Request $request, int $type = HttpKernelInterface::MAIN_REQUEST, bool $catch = false)
    {
        try {
            $response = parent::handle($request, $type, $catch); // TODO: Change the autogenerated stub
        } catch (ValidationException $e) {
            return $this->handleErrors($e);
        }
        #Illuminate\Validation\ValidationException
        return $response;
    }

    public function handleErrors(\Illuminate\Validation\ValidationException $e)
    {
        $message = $e->getMessage();
        $errors = $e->errors();

        /*
         $modx = app('modx');
         $modx->lexicon->load('auth:errors');
         $arrImplode = [];
         foreach ($arrError as $field => $value) {
             $key_error = $value[0];
             $lexi = 'auth_' . $field . '.' . $key_error;
             $text = $modx->lexicon($lexi);
             $arrImplode[$field] = $text;
         }*/

        return new JsonResponse([
            'message' => $message,
            'errors' => $errors,
        ], $e->status);
    }
}
