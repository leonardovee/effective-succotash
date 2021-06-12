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
    private $payer;
    private $payee;
    private $withdraw;
    private $deposit;
    private $getWithdrawsRepositoryStub;
    private $getDepositsRepositoryStub;

    private function makeSut()
    {
        $this->payer = new Payer();
        $this->payer->id = 1;

        $this->payee = new Payee();
        $this->payee->id = 2;

        $this->withdraw = new Withdraw();
        $this->withdraw->user = $this->payer->id;
        $this->withdraw->amount = 0;

        $this->deposit = new Deposit();
        $this->deposit->user = $this->payee->id;
        $this->deposit->amount = 0;

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

        $this->getWithdrawsRepositoryStub
            ->expects($this->once())
            ->method('get')
            ->with($this->payer->id);

        $this->sut->create($this->deposit, $this->withdraw);
    }

    public function test_should_call_get_deposits_repository_with_correct_values()
    {
        $this->makeSut();

        $this->getDepositsRepositoryStub
            ->expects($this->once())
            ->method('get')
            ->with($this->payer->id);

        $this->sut->create($this->deposit, $this->withdraw);
    }

    public function test_should_throw_if_payer_dont_have_enought_balance_to_payee()
    {
        $this->makeSut();

        $this->getDepositsRepositoryStub
            ->expects($this->once())
            ->method('get')
            ->willReturn(1000);

        $this->getWithdrawsRepositoryStub
            ->expects($this->once())
            ->method('get')
            ->willReturn(5000);

        $this->expectException(Exception::class);

        $this->sut->create($this->deposit, $this->withdraw);
    }
}
