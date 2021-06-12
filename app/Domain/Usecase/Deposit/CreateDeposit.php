<?php

namespace App\Domain\Usecase\Deposit;

use App\Domain\Model\Deposit;

interface CreateDeposit {
    public function create (Deposit $deposit) : Deposit;
}
