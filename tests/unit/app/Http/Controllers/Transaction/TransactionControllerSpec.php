<?php

use App\Http\Controllers\Transaction\TransactionController;
use App\Data\Usecase\User\DbAddUser;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class TransactionControllerTest extends TestCase
{
    private $sut;
    private $stub;

    private function makeSut()
    {
        $this->stub = $this->createMock(DbAddUser::class);

        $this->sut = new TransactionController($this->stub);
    }

    public function test_should_return_unprocessable_entity_if_validation_fails()
    {
        $this->makeSut();

        $request = new Request();

        $this->expectException(ValidationException::class);

        $this->sut->handle($request);
    }
}
