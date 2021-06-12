<?php

use App\Factories\Controllers\UserControllerFactory;
use Illuminate\Http\Request;

$router->post('/user', function (Request $request) {
    $userController = UserControllerFactory::make();
    return $userController->handle($request);
});
