<?php

/** @var \Illuminate\Routing\Router $router */

use V1\Http\Controllers\TipsController;

$router->apiResource('tips', TipsController::class)->middleware(['api-auth']);
