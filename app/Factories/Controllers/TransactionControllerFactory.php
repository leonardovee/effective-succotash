<?php

namespace App\Factories\Controllers;

use App\Data\Usecase\Transaction\DbCreateTransaction;
use App\Http\Controllers\Transaction\TransactionController;
use App\Infra\Db\MySql\Deposit\DepositRepository;
use App\Infra\Db\MySql\Payer\PayerRepository;
use App\Infra\Db\MySql\Transaction\TransactionRepository;
use App\Infra\Db\MySql\Withdraw\WithdrawRepository;
use App\Infra\Web\Authorizer\TransactionAuthorizerRepository;

class TransactionControllerFactory
{
    public static function make()
    {
        $transactionRepository = new TransactionRepository();
        $transactionAuthorizerRepository = new TransactionAuthorizerRepository();
        $depositRepository = new DepositRepository();
        $withdrawRepository = new WithdrawRepository();
        $payerRepository = new PayerRepository();
        $dbCreateTransaction = new DbCreateTransaction(
            $payerRepository,
            $withdrawRepository,
            $depositRepository,
            $transactionAuthorizerRepository,
            $transactionRepository
        );
        return new TransactionController($dbCreateTransaction);
    }
}
