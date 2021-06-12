<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function handle(Request $request)
    {
        $this->validate($request, [
            'payer' => 'required|int:user',
            'payee' => 'required|int:user',
            'amount' => 'required|int'
        ]);
    }
}
