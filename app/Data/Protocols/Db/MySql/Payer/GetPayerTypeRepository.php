<?php

namespace App\Data\Protocols\Db\MySql\Payer;

interface GetPayerTypeRepository
{
    public function getPayerType (int $user) : int;
}
