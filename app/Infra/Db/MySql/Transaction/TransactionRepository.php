<?php

namespace App\Infra\Db\MySql\Transaction;

use App\Data\Protocols\Db\MySql\Transaction\AddTransactionRepository;
use App\Domain\Model\Deposit;
use App\Domain\Model\Transaction;
use App\Domain\Model\Withdraw;
use Exception;
use Illuminate\Support\Facades\DB;
use Throwable;

class TransactionRepository implements AddTransactionRepository
{
    public function add (Deposit $depositData, Withdraw $withdrawData) : Transaction
    {
        try {
            DB::beginTransaction();

            $deposit = new \App\Models\Deposit();
            $deposit->amount = $depositData->amount;
            $deposit->user = $depositData->user;
            $deposit->save();

            $withdraw = new \App\Models\Withdraw();
            $withdraw->amount = $withdrawData->amount;
            $withdraw->user = $withdrawData->user;
            $withdraw->save();

            $transaction = new \App\Models\Transaction();
            $transaction->deposit = $deposit->id;
            $transaction->withdraw = $withdraw->id;
            $transaction->save();

            DB::commit();
        } catch (Throwable $error) {
            DB::rollBack();
            throw new Exception();
        }

        $result = new Transaction();
        $result->id = $transaction->id;

        $result->deposit = new Deposit($deposit->id, $deposit->user, $deposit->amount);
        $result->withdraw = new Withdraw($withdraw->id, $withdraw->user, $withdraw->amount);

        return $result;
    }
}
