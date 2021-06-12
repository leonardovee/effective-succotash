<?php

use App\Data\Usecase\Transaction\DbCreateTransaction;
use App\Infra\Db\MySql\Withdraw\WithdrawRepository;
use App\Infra\Db\MySql\Deposit\DepositRepository;
use App\Infra\Db\MySql\Transaction\TransactionRepository;
use App\Infra\Web\Authorizer\TransactionAuthorizerRepository;
use App\Domain\Model\Payer;
use App\Domain\Model\Deposit;
use App\Domain\Model\Payee;
use App\Domain\Model\Withdraw;
use App\Infra\Db\MySql\Payer\PayerRepository;

class DbCreateTransactionTest extends TestCase
{
    private $sut;
    private $payer;
    private $payee;
    private $withdraw;
    private $deposit;
    private $payerRepositoryStub;
    private $withdrawRepositoryStub;
    private $depositRepositoryStub;
    private $transactionAuthorizerRepositoryStub;
    private $transactionRepositoryStub;

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

        $this->payerRepositoryStub = $this->createMock(PayerRepository::class);
        $this->withdrawRepositoryStub = $this->createMock(WithdrawRepository::class);
        $this->depositRepositoryStub = $this->createMock(DepositRepository::class);
        $this->transactionAuthorizerRepositoryStub = $this->createMock(TransactionAuthorizerRepository::class);
        $this->transactionAuthorizerRepositoryStub->method('authorize')->willReturn(true);
        $this->transactionRepositoryStub = $this->createMock(TransactionRepository::class);

        $this->sut = new DbCreateTransaction(
            $this->payerRepositoryStub,
            $this->withdrawRepositoryStub,
            $this->depositRepositoryStub,
            $this->transactionAuthorizerRepositoryStub,
            $this->transactionRepositoryStub
        );
    }

    public function test_should_call_get_payer_type_repository_with_correct_values()
    {
        $this->makeSut();

        $this->payerRepositoryStub
            ->expects($this->once())
            ->method('getPayerType')
            ->with($this->payer->id);

        $this->sut->create($this->deposit, $this->withdraw);
    }

    public function test_should_throw_if_player_type_is_business()
    {
        $this->makeSut();

        $this->payerRepositoryStub
            ->expects($this->once())
            ->method('getPayerType')
            ->with($this->payer->id)
            ->willReturn(1);

        $this->expectException(Exception::class);

        $this->sut->create($this->deposit, $this->withdraw);
    }

    public function test_should_call_get_withdraws_repository_with_correct_values()
    {
        $this->makeSut();

        $this->withdrawRepositoryStub
            ->expects($this->once())
            ->method('getWithdraws')
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

        $this->withdrawRepositoryStub
            ->expects($this->once())
            ->method('getWithdraws')
            ->willReturn(5000);

        $this->expectException(Exception::class);

        $this->sut->create($this->deposit, $this->withdraw);
    }

    public function test_should_call_transaction_authorizer_with_correct_values()
    {
        $this->makeSut();

        $this->transactionAuthorizerRepositoryStub
            ->expects($this->once())
            ->method('authorize')
            ->with($this->deposit, $this->withdraw);

        $this->sut->create($this->deposit, $this->withdraw);
    }

    public function test_should_throw_if_transaction_authorizer_returns_false()
    {
        $this->makeSut();

        $this->transactionAuthorizerRepositoryStub = $this->createMock(TransactionAuthorizerRepository::class);
        $this->transactionAuthorizerRepositoryStub->method('authorize')->willReturn(false);

        $this->sut = new DbCreateTransaction(
            $this->payerRepositoryStub,
            $this->withdrawRepositoryStub,
            $this->depositRepositoryStub,
            $this->transactionAuthorizerRepositoryStub,
            $this->transactionRepositoryStub
        );

        $this->expectException(Exception::class);

        $this->sut->create($this->deposit, $this->withdraw);
    }

    public function test_should_call_add_transaction_repository_with_correct_values()
    {
        $this->makeSut();

        $this->transactionRepositoryStub
            ->expects($this->once())
            ->method('add')
            ->with($this->deposit, $this->withdraw);

        $this->sut->create($this->deposit, $this->withdraw);
    }

    public function test_should_throw_if_add_transaction_repository_throws()
    {
        $this->makeSut();

        $this->transactionRepositoryStub
            ->expects($this->once())
            ->method('add')
            ->will($this->throwException(new Exception()));

        $this->expectException(Exception::class);

        $this->sut->create($this->deposit, $this->withdraw);
    }
}
