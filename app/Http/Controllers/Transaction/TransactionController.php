<?php

namespace App\Http\Controllers\Transaction;

use App\Domain\Model\Deposit;
use App\Domain\Model\Withdraw;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;

class TransactionController extends Controller
{
    public function __construct($dbCreateTransaction)
    {
        $this->dbCreateTransaction = $dbCreateTransaction;
    }

    public function handle(Request $request)
    {
        $this->validate($request, [
            'payer' => 'required|int|exists:user,id',
            'payee' => 'required|int|exists:user,id',
            'amount' => 'required|numeric'
        ]);

        try {
            $deposit = new Deposit(0, $request->payee, $request->amount);

            $withdraw = new Withdraw(0, $request->payer, $request->amount);

            $transaction = $this->dbCreateTransaction->create($deposit, $withdraw);
        } catch (Exception $exception) {
            abort(500, $exception->getMessage());
        }
        return response()->json([
            'id' => $transaction->id
        ], 201);
    }
}
