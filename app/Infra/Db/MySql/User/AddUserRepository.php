<?php

namespace App\Infra\Db\MySql\User;

use App\Domain\Model\User;
use Illuminate\Support\Facades\Crypt;

class AddUserRepository
{
    public function add (User $userData)
    {
        $user = new \App\Models\User();

        $user->name = $userData->name;
        $user->email = $userData->email;
        $user->password = Crypt::encrypt($userData->password);
        $user->document = $userData->document;
        $user->type = $userData->type;

        $user->save();

        return $user->id;
    }
}
