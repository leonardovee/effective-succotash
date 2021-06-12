<?php

namespace App\Infra\Db\MySql\User;

use App\Models\User;
use Illuminate\Support\Facades\Crypt;

class AddUserRepository
{
    public function add (array $userData)
    {
        $user = new User();

        $user->name = $userData['name'];
        $user->email = $userData['email'];
        $user->password = Crypt::encrypt($userData['password']);
        $user->document = $userData['document'];
        $user->type = $userData['type'];

        $user->save();

        return $user->id;
    }
}
