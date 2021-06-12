<?php

namespace App\Factories\Controllers;

use App\Data\Usecase\User\DbAddUser;
use App\Http\Controllers\User\AddUserController;
use App\Infra\Db\MySql\User\AddUserRepository;

class AddUserControllerFactory
{
    public static function make()
    {
        $addUserRepository = new AddUserRepository();
        $dbAddUser = new DbAddUser($addUserRepository);
        return new AddUserController($dbAddUser);
    }
}
