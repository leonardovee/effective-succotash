<?php

use App\Data\Usecase\User\DbAddUser;
use App\Domain\Model\User;
use App\Infra\Db\MySql\User\AddUserRepository;

class DbAddUserTest extends TestCase
{
    private $sut;
    private $stub;

    private function makeUserData(): User
    {
        $user = new User();

        $user->name = 'any_name';
        $user->email = 'any_email';
        $user->password = 'any_password';
        $user->document = 'any_document';
        $user->type = 1;

        return $user;
    }

    private function makeSut()
    {
        $this->stub = $this->createMock(AddUserRepository::class);

        $this->sut = new DbAddUser($this->stub);
    }

    public function test_should_call_add_user_repository_with_correct_values()
    {
        $this->makeSut();

        $userData = $this->makeUserData();

        $this->stub->expects($this->once())->method('add')->with($userData)->willReturn(1);

        $this->sut->add($userData);
    }

    public function test_should_throw_if_add_user_repository_throws()
    {
        $this->makeSut();

        $userData = $this->makeUserData();

        $this->stub->expects($this->once())->method('add')->will(
            $this->throwException(new Exception)
        );

        $this->expectException(Exception::class);

        $this->sut->add($userData);
    }
}
