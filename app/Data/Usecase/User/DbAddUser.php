<?php

namespace App\Data\Usecase\User;

use App\Domain\Model\User;
use App\Domain\Usecase\User\AddUser;

class DbAddUser implements AddUser
{
    public function __construct($addUserRepository)
    {
        $this->addUserRepository = $addUserRepository;
    }

    public function add (User $userData): int
    {
        return $this->addUserRepository->add($userData);
    }
}
