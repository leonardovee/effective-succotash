<?php

namespace App\Domain\Usecase\Withdraw;

use App\Domain\Model\Withdraw;

interface CreateWithdraw {
    public function create (Withdraw $withdraw) : Withdraw;
}
