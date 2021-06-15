<?php

use App\Models\User;
use App\Models\Deposit;
use Faker\Factory as Faker;
use Laravel\Lumen\Testing\DatabaseMigrations;

class TransactionTest extends TestCase
{
    use DatabaseMigrations;

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

    private function makeDeposit(int $user, int $amount): Deposit
    {
        $deposit = new Deposit();

        $deposit->amount = $amount;
        $deposit->user = $user;

        $deposit->save();

        return $deposit;
    }

    public function test_should_throw_when_invalid_data_is_provided()
    {
        $this->post('/transaction')
            ->seeJsonEquals([
                'amount' => ["The amount field is required."],
                'payer' => ["The payer field is required."],
                'payee' => ["The payee field is required."]
            ]);
    }

    public function test_should_create_the_transaction_when_valida_data_is_provided()
    {
        $payee = $this->makeUser(1);
        $payer = $this->makeUser(2);
        $this->makeDeposit($payer->id, 10000);

        $this->post('/transaction', [
            'payer' => $payer->id,
            'payee' => $payee->id,
            'amount' => 10.00
        ]);

        $this->seeStatusCode(201);
    }
}
