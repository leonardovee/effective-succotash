<?php

use App\Factories\Controllers\AddUserControllerFactory;
use Illuminate\Http\Request;

$router->post('/user', function (Request $request) {
    $addUserController = AddUserControllerFactory::make();
    return $addUserController->handle($request);
});
