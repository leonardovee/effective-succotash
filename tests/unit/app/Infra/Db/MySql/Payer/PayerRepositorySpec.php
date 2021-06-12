<?php

use App\Infra\Db\MySql\Payer\PayerRepository;
use App\Models\User;
use Laravel\Lumen\Testing\DatabaseMigrations;

class PayerRepositoryTest extends TestCase
{
    use DatabaseMigrations;

    private $sut;
    private $stub;

    private function makeSut()
    {
        $this->sut = new PayerRepository($this->stub);
    }

    public function test_should_get_payer_type_on_get_payer_type_success()
    {
        $this->makeSut();

        $user = new User();
        $user->name = 'any_name';
        $user->email = 'any_email@mail.com';
        $user->password = 'any_password';
        $user->document = 'any_document';
        $user->type = 2;
        $user->save();

        $response = $this->sut->getPayerType(1);

        $this->assertSame(2, $response);
    }
}
