<?php

namespace App\Data\Protocols\Db\MySql\Withdraw;

interface GetWithdrawsRepository
{
    public function getWithdraws (int $user) : int;
}
