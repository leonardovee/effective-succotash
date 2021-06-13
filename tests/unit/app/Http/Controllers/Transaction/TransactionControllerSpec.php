<?php

use App\Data\Usecase\Transaction\DbCreateTransaction;
use App\Domain\Model\Deposit;
use App\Domain\Model\Transaction;
use App\Domain\Model\Withdraw;
use App\Http\Controllers\Transaction\TransactionController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Faker\Factory as Faker;
use Laravel\Lumen\Testing\DatabaseMigrations;

class TransactionControllerTest extends TestCase
{
    use DatabaseMigrations;

    private $sut;
    private $stub;
    private User $payer;
    private User $payee;

    private function makeUser(int $type): User
    {
        $faker = Faker::create();

        $user = new User();

        $user->name = $faker->name();
        $user->email = $faker->email();
        $user->password = $faker->password(255);
        $user->document = $faker->text(14);
        $user->type = $type;

        $user->save();

        return $user;
    }

    private function makeUsers(): void
    {
        $this->payee = $this->makeUser(1);
        $this->payer = $this->makeUser(2);
    }

    private function makeRequest(): Request
    {
        return new Request([
            'payer' => $this->payer->id,
            'payee' => $this->payee->id,
            'amount' => 10.00
        ]);
    }

    private function makeSut()
    {
        $this->makeUsers();

        $this->stub = $this->createMock(DbCreateTransaction::class);

        $this->deposit = new Deposit(0, $this->payee->id, 10.00);

        $this->withdraw = new Withdraw(0, $this->payer->id, 10.00);

        $this->transaction = new Transaction();
        $this->transaction->id = 25;

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

        $this->stub->expects($this->once())
            ->method('create')
            ->with($this->deposit, $this->withdraw)
            ->willReturn($this->transaction);

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

    public function test_should_return_transaction_id_on_sucess()
    {
        $this->makeSut();

        $request = $this->makeRequest();

        $this->stub->expects($this->once())
            ->method('create')
            ->willReturn($this->transaction);

        $response = $this->sut->handle($request);

        $this->assertSame($this->transaction->id, $response->getData()->id);
        $this->assertSame(201, $response->getStatusCode());
    }
}
