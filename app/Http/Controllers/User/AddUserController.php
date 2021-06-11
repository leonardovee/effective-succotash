<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AddUserController extends Controller
{
    private $dbAddUser;

    public function __construct($dbAddUser)
    {
        $this->dbAddUser = $dbAddUser;
    }

    public function handle(Request $request): JsonResponse
    {
        $this->validate($request, [
            'name' => 'required|string',
            'email' => 'required|string',
            'password' => 'required|string',
            'publicRegistry' => 'required|string',
            'type' => 'required|int'
        ]);

        $this->dbAddUser->add([
            $request->name,
            $request->email,
            $request->password,
            $request->publicRegistry,
            $request->type
        ]);

        return response()->json([]);
    }
}
