<?php

namespace App\Data\Protocols\Db\MySql\Transaction;

use App\Domain\Model\Deposit;
use App\Domain\Model\Transaction;
use App\Domain\Model\Withdraw;

interface AddTransactionRepository
{
    public function add (Deposit $deposit, Withdraw $withdraw) : Transaction;
}
