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
        $depositRepository,
        $transactionAuthorizerRepository,
        $transactionRepository
    ) {
        $this->payerRepository = $payerRepository;
        $this->withdrawRepository = $withdrawRepository;
        $this->depositRepository = $depositRepository;
        $this->transactionAuthorizerRepository = $transactionAuthorizerRepository;
        $this->transactionRepository = $transactionRepository;
    }

    public function create (Deposit $deposit, Withdraw $withdraw): Transaction
    {
        $payerType = $this->payerRepository->getPayerType($withdraw->user);
        if ($payerType === 1) {
            throw new Exception('Bussiness shouldn\'t pay');
        }

        $withdraws = $this->withdrawRepository->getWithdraws($withdraw->user);
        $deposits = $this->depositRepository->getDeposits($withdraw->user);
        $balance = $deposits - $withdraws;

        if ($withdraw->amount > $balance) {
            throw new Exception('Not enought balance');
        }

        $isTransactionAuthorized = $this->transactionAuthorizerRepository->authorize($deposit, $withdraw);
        if (!$isTransactionAuthorized) {
            throw new Exception('Not authorized');
        }

        return $this->transactionRepository->add($deposit, $withdraw);
    }
}
