<?php

namespace App\Services;

use App\Repositories\UserRepository;

class AuthService
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function authenticate($username, $password)
    {
        $username = trim((string)$username);
        $password = trim((string)$password);

        if ($username === '' || $password === '') {
            return null;
        }

        return $this->userRepository->findByCredentials($username, $password);
    }
}
