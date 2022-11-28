<?php
/**
 * Created by Andrey Stepanenko.
 * User: webnitros
 * Date: 06.11.2022
 * Time: 17:25
 */

use AppM\Application;

$app = Application::create(function (\AppM\Route $Route) {
    $Route->prefixStack('/api')->group([], function (\AppM\Route $r) {

        // Запуск нашего контроллера
        $r->post('/page', [\AppM\Http\Controllers\Page\MainController::class, 'index']);
    });
    return $Route;
});

// Регистрация MODX компонентов
$app->singleton('modx', function () use ($modx) {
    return $modx;
});


$app->singleton('pdotools.service', function () use ($modx) {
    $modx = app('modx');
    if ($pdoTools = $modx->getService('pdoFetch')) {
        $pdoTools->setConfig([]);
    }
    return $pdoTools;
});


Application::regFacade($app);
