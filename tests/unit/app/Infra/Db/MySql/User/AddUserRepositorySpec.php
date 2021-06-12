<?php

use App\Infra\Db\MySql\User\AddUserRepository;
use Laravel\Lumen\Testing\DatabaseMigrations;

class AddUserRepositoryTest extends TestCase
{
    use DatabaseMigrations;

    private $sut;
    private $stub;

    private function makeUserData(): array
    {
        return [
            'name' => 'any_name',
            'email' => 'any_email',
            'password' => 'any_password',
            'document' => 'any_document',
            'type' => 1
        ];
    }

    private function makeSut()
    {
        $this->sut = new AddUserRepository($this->stub);
    }

    public function test_should_add_a_user_on_success()
    {
        $this->makeSut();

        $request = $this->makeUserData();

        $this->sut->add($request);

        unset($request['password']);
        $this->seeInDatabase('user', $request);
    }
}
