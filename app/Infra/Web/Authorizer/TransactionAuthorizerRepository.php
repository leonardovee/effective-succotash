<?php

namespace App\Infra\Web\Authorizer;

use App\Data\Protocols\Web\Authorizer\AuthorizeTransactionAuthorizerRepository;
use App\Domain\Model\Deposit;
use App\Domain\Model\Withdraw;
use Illuminate\Support\Facades\Http;

class TransactionAuthorizerRepository implements AuthorizeTransactionAuthorizerRepository
{
    public function authorize (Deposit $deposit, Withdraw $withdraw) : bool
    {
        $response = Http::post(env('AUTHORIZER_URL'));
        if (!$response) {
            return false;
        }
        return true;
    }
}
