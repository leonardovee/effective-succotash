<?php

namespace App\Data\Usecase\Transaction;

use App\Domain\Model\Transaction;
use App\Domain\Model\Deposit;
use App\Domain\Model\Withdraw;
use App\Domain\Usecase\Transaction\CreateTransaction;

class DbCreateTransaction implements CreateTransaction
{
    public function __construct($getWithdrawsRepository)
    {
        $this->getWithdrawsRepository = $getWithdrawsRepository;
    }

    public function create (Deposit $deposit, Withdraw $withdraw): Transaction
    {
        $this->getWithdrawsRepository->get($withdraw->user);
        return new Transaction();
    }
}
