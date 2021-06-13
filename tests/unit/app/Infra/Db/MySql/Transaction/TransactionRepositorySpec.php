<?php

use App\Infra\Db\MySql\Transaction\TransactionRepository;
use App\Domain\Model\Deposit;
use App\Domain\Model\Withdraw;
use App\Domain\Model\Transaction;
use App\Models\User;
use Faker\Factory as Faker;
use Laravel\Lumen\Testing\DatabaseMigrations;

class TransactionRepositoryTest extends TestCase
{
    use DatabaseMigrations;

    private $sut;
    private $stub;

    private function makeSut()
    {
        $this->sut = new TransactionRepository($this->stub);
    }


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

    public function test_should_return_transaction_on_success()
    {
        $this->makeSut();

        $payer = $this->makeUser(2);
        $payee = $this->makeUser(1);

        $withdraw = new Withdraw();
        $withdraw->user = $payer->id;
        $withdraw->amount = 1000;

        $deposit = new Deposit();
        $deposit->user = $payee->id;
        $deposit->amount = 1000;

        $response = $this->sut->add($deposit, $withdraw);

        $this->assertInstanceOf(Transaction::class, $response);
    }
}
