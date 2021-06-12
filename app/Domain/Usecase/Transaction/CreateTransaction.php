<?php

namespace App\Domain\Usecase\Transaction;

use App\Domain\Model\Transaction;
use App\Domain\Model\Deposit;
use App\Domain\Model\Withdraw;

interface CreateTransaction {
    public function create (Deposit $deposit, Withdraw $withdraw) : Transaction;
}
