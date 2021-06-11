<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AddUserController extends Controller
{
    public function handle(Request $request): JsonResponse
    {
        $this->validate($request, [
            'name' => 'required|string',
            'email' => 'required|string',
            'password' => 'required|string',
            'publicRegistry' => 'required|string',
            'type' => 'required|int'
        ]);
    }
}
