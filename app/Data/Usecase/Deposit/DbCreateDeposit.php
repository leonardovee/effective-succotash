<?php

namespace App\Data\Usecase\Deposit;

use App\Domain\Usecase\Deposit\CreateDeposit;
use App\Domain\Model\Deposit;

class DbCreateDeposit implements CreateDeposit
{
    public function create (Deposit $deposit) : Deposit
    {
        return new Deposit();
    }
}
