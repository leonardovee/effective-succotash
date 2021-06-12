<?php

namespace App\Http\Controllers\Transaction;

use App\Domain\Model\Deposit;
use App\Domain\Model\Withdraw;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function __construct($dbCreateTransaction)
    {
        $this->dbCreateTransaction = $dbCreateTransaction;
    }

    public function handle(Request $request)
    {
        $this->validate($request, [
            'payer' => 'required|int:user',
            'payee' => 'required|int:user',
            'amount' => 'required|numeric'
        ]);

        $deposit = new Deposit();
        $deposit->user = $request->payee;
        $deposit->amount = $request->amount;

        $withdraw = new Withdraw();
        $withdraw->user = $request->payer;
        $withdraw->amount = $request->amount;

        $this->dbCreateTransaction->create($deposit, $withdraw);
    }
}
