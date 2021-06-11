<?php

namespace App\Data\Usecase\User;

class DbAddUser
{
    public function __construct($addUserRepository)
    {
        $this->addUserRepository = $addUserRepository;
    }

    public function add (array $request)
    {
        $this->addUserRepository->add($request);
    }
}
