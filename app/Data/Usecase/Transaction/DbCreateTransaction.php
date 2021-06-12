<?php

namespace App\Data\Usecase\Transaction;

use App\Domain\Model\Transaction;
use App\Domain\Model\Deposit;
use App\Domain\Model\Withdraw;
use App\Domain\Usecase\Transaction\CreateTransaction;
use Exception;

class DbCreateTransaction implements CreateTransaction
{
    public function __construct(
        $getPayerTypeRepository,
        $getWithdrawsRepository,
        $getDepositsRepository,
        $getTransactionAuthorizerRepository
    ) {
        $this->getPayerTypeRepository = $getPayerTypeRepository;
        $this->getWithdrawsRepository = $getWithdrawsRepository;
        $this->getDepositsRepository = $getDepositsRepository;
        $this->getTransactionAuthorizerRepository = $getTransactionAuthorizerRepository;
    }

    public function create (Deposit $deposit, Withdraw $withdraw): Transaction
    {
        $payerType = $this->getPayerTypeRepository->get($withdraw->user);
        if ($payerType === 1) {
            throw new Exception();
        }
        $withdraws = $this->getWithdrawsRepository->get($withdraw->user);
        $deposits = $this->getDepositsRepository->get($withdraw->user);
        $balance = $deposits - $withdraws;
        if ($withdraw->amount > $balance) {
            throw new Exception();
        }
        $this->getTransactionAuthorizerRepository->get($deposit, $withdraw);
        return new Transaction();
    }
}
