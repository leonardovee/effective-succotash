<?php

use App\Http\Controllers\User\AddUserController;
use App\Data\Usecase\User\DbAddUser;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AddUserControllerTest extends TestCase
{
    private $sut;
    private $stub;

    private function makeSut()
    {
        $this->stub = $this->createMock(DbAddUser::class);

        $this->sut = new AddUserController($this->stub);
    }

    public function test_should_return_unprocessable_entity_if_validation_fails()
    {
        $this->makeSut();

        $request = new Request();

        $this->expectException(ValidationException::class);

        $this->sut->handle($request);
    }

    public function test_should_call_db_add_user_with_correct_values()
    {
        $this->makeSut();

        $request = new Request([
            'name' => 'any_name',
            'email' => 'any_email',
            'password' => 'any_password',
            'publicRegistry' => 'any_public_registry',
            'type' => 1
        ]);

        $this->stub->expects($this->once())->method('add')->with([
            $request->name,
            $request->email,
            $request->password,
            $request->publicRegistry,
            $request->type
        ]);

        $this->sut->handle($request);
    }
}
