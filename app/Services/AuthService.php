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

        $user = $this->userRepository->findByUsername($username);
        if ($user === null) {
            return null;
        }

        $storedPassword = (string)($user['Password'] ?? '');
        if ($storedPassword === '') {
            return null;
        }

        $passwordInfo = password_get_info($storedPassword);
        $isHashedPassword = isset($passwordInfo['algo']) && $passwordInfo['algo'] !== null && $passwordInfo['algo'] !== 0;

        if ($isHashedPassword) {
            if (!password_verify($password, $storedPassword)) {
                return null;
            }

            if (password_needs_rehash($storedPassword, PASSWORD_DEFAULT)) {
                $newHash = password_hash($password, PASSWORD_DEFAULT);
                if (is_string($newHash) && $newHash !== '') {
                    $this->userRepository->updatePasswordHash((int)$user['ID'], $newHash);
                }
            }
        } else {
            if (!hash_equals($storedPassword, $password)) {
                return null;
            }

            $newHash = password_hash($password, PASSWORD_DEFAULT);
            if (is_string($newHash) && $newHash !== '') {
                $this->userRepository->updatePasswordHash((int)$user['ID'], $newHash);
            }
        }

        unset($user['Password']);
        return $user;
    }
}
