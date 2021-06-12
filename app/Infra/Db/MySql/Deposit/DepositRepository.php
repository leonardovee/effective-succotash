<?php

namespace App\Infra\Db\MySql\Deposit;

use App\Data\Protocols\Db\MySql\Deposit\GetDepositsRepository;
use App\Models\Deposit;

class DepositRepository implements GetDepositsRepository
{
    public function getDeposits (int $user) : int
    {
        return Deposit::where('user', $user)->get()->sum('amount');
    }
}
