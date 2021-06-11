<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Exception;

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

        try {
            $userId = $this->dbAddUser->add([
                $request->name,
                $request->email,
                $request->password,
                $request->publicRegistry,
                $request->type
            ]);

            return response()->json([
                'id' => $userId
            ], 201);
        } catch (Exception $exception) {
            abort(500, 'we couldn\'t process your request');
        }
    }
}
