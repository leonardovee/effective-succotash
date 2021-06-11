<?php

use App\Http\Controllers\User\AddUserController;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AddUserControllerTest extends TestCase
{
    public function test_unprocessable_entity_is_returned_if_validation_fails()
    {
        $sut = new AddUserController();
        $request = new Request();

        $this->expectException(ValidationException::class);

        $sut->handle($request);
    }
}
