<?php

use App\Data\Usecase\Transaction\DbCreateTransaction;
use App\Infra\Db\MySql\Withdraw\GetWithdrawsRepository;
use App\Infra\Db\MySql\Deposit\DepositRepository;
use App\Infra\Db\MySql\Payer\GetPayerTypeRepository;
use App\Infra\Db\MySql\Transaction\AddTransactionRepository;
use App\Infra\Web\Authorizer\GetTransactionAuthorizerRepository;
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
    private $getPayerTypeRepositoryStub;
    private $getWithdrawsRepositoryStub;
    private $depositRepositoryStub;
    private $getTransactionAuthorizerRepositoryStub;
    private $addTransactionRepositoryStub;

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

        $this->getPayerTypeRepositoryStub = $this->createMock(GetPayerTypeRepository::class);
        $this->getWithdrawsRepositoryStub = $this->createMock(GetWithdrawsRepository::class);
        $this->depositRepositoryStub = $this->createMock(DepositRepository::class);
        $this->getTransactionAuthorizerRepositoryStub = $this->createMock(GetTransactionAuthorizerRepository::class);
        $this->getTransactionAuthorizerRepositoryStub->method('get')->willReturn(true);
        $this->addTransactionRepositoryStub = $this->createMock(AddTransactionRepository::class);

        $this->sut = new DbCreateTransaction(
            $this->getPayerTypeRepositoryStub,
            $this->getWithdrawsRepositoryStub,
            $this->depositRepositoryStub,
            $this->getTransactionAuthorizerRepositoryStub,
            $this->addTransactionRepositoryStub
        );
    }

    public function test_should_call_get_payer_type_repository_with_correct_values()
    {
        $this->makeSut();

        $this->getPayerTypeRepositoryStub
            ->expects($this->once())
            ->method('get')
            ->with($this->payer->id);

        $this->sut->create($this->deposit, $this->withdraw);
    }

    public function test_should_throw_if_player_type_is_business()
    {
        $this->makeSut();

        $this->getPayerTypeRepositoryStub
            ->expects($this->once())
            ->method('get')
            ->with($this->payer->id)
            ->willReturn(1);

        $this->expectException(Exception::class);

        $this->sut->create($this->deposit, $this->withdraw);
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

        $this->depositRepositoryStub
            ->expects($this->once())
            ->method('getDeposits')
            ->with($this->payer->id);

        $this->sut->create($this->deposit, $this->withdraw);
    }

    public function test_should_throw_if_payer_dont_have_enought_balance_to_payee()
    {
        $this->makeSut();

        $this->depositRepositoryStub
            ->expects($this->once())
            ->method('getDeposits')
            ->willReturn(1000);

        $this->getWithdrawsRepositoryStub
            ->expects($this->once())
            ->method('get')
            ->willReturn(5000);

        $this->expectException(Exception::class);

        $this->sut->create($this->deposit, $this->withdraw);
    }

    public function test_should_call_transaction_authorizer_with_correct_values()
    {
        $this->makeSut();

        $this->getTransactionAuthorizerRepositoryStub
            ->expects($this->once())
            ->method('get')
            ->with($this->deposit, $this->withdraw);

        $this->sut->create($this->deposit, $this->withdraw);
    }

    public function test_should_throw_if_transaction_authorizer_returns_false()
    {
        $this->makeSut();

        $this->getTransactionAuthorizerRepositoryStub = $this->createMock(GetTransactionAuthorizerRepository::class);
        $this->getTransactionAuthorizerRepositoryStub->method('get')->willReturn(false);

        $this->sut = new DbCreateTransaction(
            $this->getPayerTypeRepositoryStub,
            $this->getWithdrawsRepositoryStub,
            $this->depositRepositoryStub,
            $this->getTransactionAuthorizerRepositoryStub,
            $this->addTransactionRepositoryStub
        );

        $this->expectException(Exception::class);

        $this->sut->create($this->deposit, $this->withdraw);
    }

    public function test_should_call_add_transaction_repository_with_correct_values()
    {
        $this->makeSut();

        $this->addTransactionRepositoryStub
            ->expects($this->once())
            ->method('add')
            ->with($this->deposit, $this->withdraw);

        $this->sut->create($this->deposit, $this->withdraw);
    }

    public function test_should_throw_if_add_transaction_repository_throws()
    {
        $this->makeSut();

        $this->addTransactionRepositoryStub
            ->expects($this->once())
            ->method('add')
            ->will($this->throwException(new Exception()));

        $this->expectException(Exception::class);

        $this->sut->create($this->deposit, $this->withdraw);
    }
}
