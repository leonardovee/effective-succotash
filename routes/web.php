<?php

use App\Factories\Controllers\UserControllerFactory;
use App\Factories\Controllers\TransactionControllerFactory;
use Illuminate\Http\Request;

$router->post('/user', function (Request $request) {
    $userController = UserControllerFactory::make();
    return $userController->handle($request);
});

$router->post('/transaction', function (Request $request) {
    $transactionController = TransactionControllerFactory::make();
    return $transactionController->handle($request);
});
