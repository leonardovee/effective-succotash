<?php

namespace App\Data\Usecase\Withdraw;

use App\Domain\Usecase\Withdraw\CreateWithdraw;
use App\Domain\Model\Withdraw;

class DbCreateWithdraw implements CreateWithdraw
{
    public function create (Withdraw $withdraw) : Withdraw
    {
        return new Withdraw();
    }
}
