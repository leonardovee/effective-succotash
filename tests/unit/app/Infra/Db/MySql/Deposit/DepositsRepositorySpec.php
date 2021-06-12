<?php

use App\Infra\Db\MySql\Deposit\DepositRepository;
use App\Models\Deposit;
use App\Models\User;
use Laravel\Lumen\Testing\DatabaseMigrations;

class DepositRepositoryTest extends TestCase
{
    use DatabaseMigrations;

    private $sut;
    private $stub;

    private function makeSut()
    {
        $this->sut = new DepositRepository($this->stub);
    }

    public function test_should_get_deposits_on_get_deposits_success()
    {
        $this->makeSut();

        $user = new User();
        $user->name = 'any_name';
        $user->email = 'any_email@mail.com';
        $user->password = 'any_password';
        $user->document = 'any_document';
        $user->type = 1;
        $user->save();

        $deposit = new Deposit();
        $deposit->user = 1;
        $deposit->amount = 1000;
        $deposit->save();

        $deposit = new Deposit();
        $deposit->user = 1;
        $deposit->amount = 1000;
        $deposit->save();

        $response = $this->sut->getDeposits(1);

        $this->assertSame(2000, $response);
    }
}
