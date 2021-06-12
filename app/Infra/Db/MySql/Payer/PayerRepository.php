<?php

namespace App\Infra\Db\MySql\Payer;

use App\Data\Protocols\Db\MySql\Payer\GetPayerTypeRepository;
use App\Models\User;

class PayerRepository implements GetPayerTypeRepository
{
    public function getPayerType (int $user) : int
    {
        return User::find($user)->type;
    }
}
