<?php

namespace App\Infra\Db\MySql\User;

use App\Models\User;

class AddUserRepository
{
    public function add (array $userData)
    {
        $user = new User($userData);
        $user->save();

        return $user->id;
    }
}
