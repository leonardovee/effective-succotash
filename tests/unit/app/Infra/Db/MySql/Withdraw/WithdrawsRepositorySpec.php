<?php

use App\Infra\Db\MySql\Withdraw\WithdrawRepository;
use App\Models\Withdraw;
use App\Models\User;
use Laravel\Lumen\Testing\DatabaseMigrations;

class WithdrawRepositoryTest extends TestCase
{
    use DatabaseMigrations;

    private $sut;
    private $stub;

    private function makeSut()
    {
        $this->sut = new WithdrawRepository($this->stub);
    }

    public function test_should_get_withdraws_on_get_withdraws_success()
    {
        $this->makeSut();

        $user = new User();
        $user->name = 'any_name';
        $user->email = 'any_email@mail.com';
        $user->password = 'any_password';
        $user->document = 'any_document';
        $user->type = 1;
        $user->save();

        $Withdraw = new Withdraw();
        $Withdraw->user = 1;
        $Withdraw->amount = 1000;
        $Withdraw->save();

        $Withdraw = new Withdraw();
        $Withdraw->user = 1;
        $Withdraw->amount = 1000;
        $Withdraw->save();

        $response = $this->sut->getWithdraws(1);

        $this->assertSame(2000, $response);
    }
}
