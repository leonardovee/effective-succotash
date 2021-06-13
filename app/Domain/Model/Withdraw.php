<?php

namespace App\Domain\Model;

class Withdraw
{
    public int $id;
    public string $user;
    public int $amount;

    public function __construct(int $id = 0, string $user = '', int $amount = 0)
    {
        $this->id = $id;
        $this->user = $user;
        $this->amount = $amount;
    }
}
