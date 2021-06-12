<?php

namespace App\Data\Protocols\Db\MySql\Deposit;

interface GetDepositsRepository
{
    public function getDeposits (int $user) : int;
}
