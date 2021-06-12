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
            'email' => 'required|unique:user|string',
            'password' => 'required|string',
            'document' => 'required|unique:user|string',
            'type' => 'required|int'
        ]);

        try {
            $userId = $this->dbAddUser->add([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
                'document' => $request->document,
                'type' => $request->type
            ]);

            return response()->json([
                'id' => $userId
            ], 201);
        } catch (Exception $exception) {
            abort(500);
        }
    }
}
