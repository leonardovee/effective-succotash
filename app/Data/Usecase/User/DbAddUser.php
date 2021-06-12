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
        return $this->addUserRepository->add($request);
    }
}
