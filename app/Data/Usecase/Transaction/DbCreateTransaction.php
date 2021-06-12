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
        $payerRepository,
        $withdrawRepository,
        $depositsRepository,
        $transactionAuthorizerRepository,
        $addTransactionRepository
    ) {
        $this->payerRepository = $payerRepository;
        $this->withdrawRepository = $withdrawRepository;
        $this->depositsRepository = $depositsRepository;
        $this->transactionAuthorizerRepository = $transactionAuthorizerRepository;
        $this->addTransactionRepository = $addTransactionRepository;
    }

    public function create (Deposit $deposit, Withdraw $withdraw): Transaction
    {
        $payerType = $this->payerRepository->getPayerType($withdraw->user);
        if ($payerType === 1) {
            throw new Exception();
        }

        $withdraws = $this->withdrawRepository->getWithdraws($withdraw->user);
        $deposits = $this->depositsRepository->getDeposits($withdraw->user);
        $balance = $deposits - $withdraws;

        if ($withdraw->amount > $balance) {
            throw new Exception();
        }

        $isTransactionAuthorized = $this->transactionAuthorizerRepository->authorize($deposit, $withdraw);
        if (!$isTransactionAuthorized) {
            throw new Exception();
        }

        return $this->addTransactionRepository->add($deposit, $withdraw);
    }
}
