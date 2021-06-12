<?php

use App\Domain\Model\User;
use App\Infra\Db\MySql\User\AddUserRepository;
use Laravel\Lumen\Testing\DatabaseMigrations;

class AddUserRepositoryTest extends TestCase
{
    use DatabaseMigrations;

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
        $this->sut = new AddUserRepository($this->stub);
    }

    public function test_should_add_a_user_on_success()
    {
        $this->makeSut();

        $userData = $this->makeUserData();

        $this->sut->add($userData);

        unset($userData->password);
        $this->seeInDatabase('user', [
            'name' => $userData->name,
            'email' => $userData->email,
            'document' => $userData->document,
            'type' => $userData->type
        ]);
    }
}
