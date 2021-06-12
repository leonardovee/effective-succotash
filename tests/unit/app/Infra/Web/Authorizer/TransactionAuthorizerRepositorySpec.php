<?php

use App\Infra\Web\Authorizer\TransactionAuthorizerRepository;
use App\Domain\Model\Deposit;
use App\Domain\Model\Withdraw;
use Illuminate\Support\Facades\Http;
use Laravel\Lumen\Testing\DatabaseMigrations;

class TransactionAuthorizerRepositoryTest extends TestCase
{
    use DatabaseMigrations;

    private $sut;
    private $stub;

    private function makeSut()
    {
        $this->sut = new TransactionAuthorizerRepository($this->stub);
    }

    public function test_should_return_false_if_no_valid_response_is_returned()
    {
        $this->makeSut();

        $deposit = new Deposit();
        $deposit->user = 1;
        $deposit->amount = 1000;

        $withdraw = new Withdraw();
        $withdraw->user = 2;
        $withdraw->amount = 1000;

        Http::shouldReceive('post')
            ->once()
            ->with(env('AUTHORIZER_URL'))
            ->andReturn('');

        $response = $this->sut->authorize($deposit, $withdraw);

        $this->assertSame(false, $response);
    }

    public function test_should_return_true_if_a_valid_response_is_returned()
    {
        $this->makeSut();

        $deposit = new Deposit();
        $deposit->user = 1;
        $deposit->amount = 1000;

        $withdraw = new Withdraw();
        $withdraw->user = 2;
        $withdraw->amount = 1000;

        Http::shouldReceive('post')
            ->once()
            ->with(env('AUTHORIZER_URL'))
            ->andReturn('valid');

        $response = $this->sut->authorize($deposit, $withdraw);

        $this->assertSame(true, $response);
    }
}
