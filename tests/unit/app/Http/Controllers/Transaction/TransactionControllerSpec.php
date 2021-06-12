<?php

use App\Data\Usecase\Transaction\DbCreateTransaction;
use App\Domain\Model\Deposit;
use App\Domain\Model\Withdraw;
use App\Http\Controllers\Transaction\TransactionController;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class TransactionControllerTest extends TestCase
{
    private $sut;
    private $stub;

    private function makeRequest(): Request
    {
        return new Request([
            'payer' => 1,
            'payee' => 2,
            'amount' => 10.00
        ]);
    }

    private function makeSut()
    {
        $this->stub = $this->createMock(DbCreateTransaction::class);

        $this->deposit = new Deposit();
        $this->deposit->user = 2;
        $this->deposit->amount = 10.00;

        $this->withdraw = new Withdraw();
        $this->withdraw->user = 1;
        $this->withdraw->amount = 10.00;

        $this->sut = new TransactionController($this->stub);
    }

    public function test_should_return_unprocessable_entity_if_validation_fails()
    {
        $this->makeSut();

        $request = new Request();

        $this->expectException(ValidationException::class);

        $this->sut->handle($request);
    }

    public function test_should_call_db_create_transaction_with_correct_values()
    {
        $this->makeSut();

        $request = $this->makeRequest();

        $this->stub->expects($this->once())->method('create')->with($this->deposit, $this->withdraw);

        $this->sut->handle($request);
    }

    public function test_should_return_server_error_if_db_create_transaction_throws()
    {
        $this->makeSut();

        $request = $this->makeRequest();

        $this->stub->expects($this->once())->method('create')->will(
            $this->throwException(new Exception)
        );

        $this->expectException(HttpException::class);

        $this->sut->handle($request);
    }
}
