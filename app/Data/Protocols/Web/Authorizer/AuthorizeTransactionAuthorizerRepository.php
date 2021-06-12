<?php

namespace App\Data\Protocols\Web\Authorizer;

use App\Domain\Model\Deposit;
use App\Domain\Model\Withdraw;

interface AuthorizeTransactionAuthorizerRepository
{
    public function authorize (Deposit $deposit, Withdraw $withdraw) : bool;
}
