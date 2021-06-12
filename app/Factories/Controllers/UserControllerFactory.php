<?php

namespace App\Factories\Controllers;

use App\Data\Usecase\User\DbAddUser;
use App\Http\Controllers\User\UserController;
use App\Infra\Db\MySql\User\AddUserRepository;

class UserControllerFactory
{
    public static function make()
    {
        $addUserRepository = new AddUserRepository();
        $dbAddUser = new DbAddUser($addUserRepository);
        return new UserController($dbAddUser);
    }
}
