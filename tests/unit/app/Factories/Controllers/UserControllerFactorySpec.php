<?php

use App\Factories\Controllers\UserControllerFactory;
use App\Http\Controllers\User\UserController;

class UserControllerFactoryTest extends TestCase
{
    public function test_shoul_return_user_controller()
    {
        $sut = new UserControllerFactory();

        $response = $sut->make();

        $this->assertInstanceOf(UserController::class, $response);
    }
}
