<?php

use App\Factories\Controllers\TransactionControllerFactory;
use App\Http\Controllers\Transaction\TransactionController;

class TransactionControllerFactoryTest extends TestCase
{
    public function test_shoul_return_transaction_controller()
    {
        $sut = new TransactionControllerFactory();

        $response = $sut->make();

        $this->assertInstanceOf(TransactionController::class, $response);
    }
}
