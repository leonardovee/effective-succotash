<?php

namespace App\Domain\Model;

class Transaction {
    public int $id;
    public Deposit $deposit;
    public Withdraw $withdraw;
}
