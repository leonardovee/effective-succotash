<?php

use App\Data\Usecase\Transaction\DbCreateTransaction;
use App\Infra\Db\MySql\Withdraw\GetWithdrawsRepository;
use App\Infra\Db\MySql\Deposit\GetDepositsRepository;
use App\Domain\Model\Payer;
use App\Domain\Model\Deposit;
use App\Domain\Model\Payee;
use App\Domain\Model\Withdraw;

class DbCreateTransactionTest extends TestCase
{
    private $sut;
    private $getWithdrawsRepositoryStub;
    private $getDepositsRepositoryStub;

    private function makeSut()
    {
        $this->getWithdrawsRepositoryStub = $this->createMock(GetWithdrawsRepository::class);
        $this->getDepositsRepositoryStub = $this->createMock(GetDepositsRepository::class);

        $this->sut = new DbCreateTransaction(
            $this->getWithdrawsRepositoryStub,
            $this->getDepositsRepositoryStub
        );
    }

    public function test_should_call_get_withdraws_repository_with_correct_values()
    {
        $this->makeSut();

        $payer = new Payer();
        $payer->id = 1;

        $payee = new Payee();
        $payee->id = 2;

        $withdraw = new Withdraw();
        $withdraw->user = $payer->id;
        $withdraw->amount = 100;

        $deposit = new Deposit();
        $deposit->user = $payee->id;
        $deposit->amount = 100;

        $this->getWithdrawsRepositoryStub
            ->expects($this->once())
            ->method('get')
            ->with($payer->id);

        $this->sut->create($deposit, $withdraw);
    }

    public function test_should_call_get_deposits_repository_with_correct_values()
    {
        $this->makeSut();

        $payer = new Payer();
        $payer->id = 1;

        $payee = new Payee();
        $payee->id = 2;

        $withdraw = new Withdraw();
        $withdraw->user = $payer->id;
        $withdraw->amount = 100;

        $deposit = new Deposit();
        $deposit->user = $payee->id;
        $deposit->amount = 100;

        $this->getDepositsRepositoryStub
            ->expects($this->once())
            ->method('get')
            ->with($payer->id);

        $this->sut->create($deposit, $withdraw);
    }
}
