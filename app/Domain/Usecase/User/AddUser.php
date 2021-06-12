<?php

namespace App\Domain\Usecase\User;

use App\Domain\Model\User;

interface AddUser {
    public function add (User $userData) : int;
}
