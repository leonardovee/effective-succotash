<?php

namespace App\Infra\Db\MySql\Withdraw;

use App\Data\Protocols\Db\MySql\Withdraw\GetWithdrawsRepository;
use App\Models\Withdraw;

class WithdrawRepository implements GetWithdrawsRepository
{
    public function getWithdraws (int $user): int
    {
        return Withdraw::where('user', $user)->get()->sum('amount');
    }
}
