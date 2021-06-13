<?php

namespace App\Http\Controllers\User;

use App\Domain\Model\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Exception;

class UserController extends Controller
{
    private $dbAddUser;

    public function __construct($dbAddUser)
    {
        $this->dbAddUser = $dbAddUser;
    }

    public function handle(Request $request): JsonResponse
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'email' => 'required|unique:user|string|max:255',
            'password' => 'required|string|max:255',
            'document' => 'required|unique:user|string|max:14',
            'type' => 'required|int|max:2'
        ]);

        try {
            $user = new User();

            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = $request->password;
            $user->document = $request->document;
            $user->type = $request->type;

            $userId = $this->dbAddUser->add($user);
        } catch (Exception $exception) {
            abort(500);
        }
        return response()->json([
            'id' => $userId
        ], 201);
    }
}
