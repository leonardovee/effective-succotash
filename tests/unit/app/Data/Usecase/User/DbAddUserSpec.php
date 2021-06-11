<?php

use App\Data\Usecase\User\DbAddUser;
use App\Infra\Db\MySql\User\AddUserRepository;
use Illuminate\Http\Request;

class DbAddUserTest extends TestCase
{
    private $sut;
    private $stub;

    private function makeRequest(): array
    {
        return [
            'name' => 'any_name',
            'email' => 'any_email',
            'password' => 'any_password',
            'publicRegistry' => 'any_public_registry',
            'type' => 1
        ];
    }

    private function makeSut()
    {
        $this->stub = $this->createMock(AddUserRepository::class);

        $this->sut = new DbAddUser($this->stub);
    }

    public function test_should_call_add_user_repository_with_correct_values()
    {
        $this->makeSut();

        $request = $this->makeRequest();

        $this->stub->expects($this->once())->method('add')->with($request);

        $this->sut->add($request);
    }

    public function test_should_throw_if_add_user_repository_throws()
    {
        $this->makeSut();

        $request = $this->makeRequest();

        $this->stub->expects($this->once())->method('add')->will(
            $this->throwException(new Exception)
        );

        $this->expectException(Exception::class);

        $this->sut->add($request);
    }
}
