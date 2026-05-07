<?php

namespace App\Services;

use App\Domain\Models\User;
use App\Repositories\UserRepository;

class AuthService
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function authenticate($username, $password): ?User
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

        $storedPassword = $user->password ?? '';
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
                    $this->userRepository->updatePasswordHash($user->id, $newHash);
                }
            }
        } else {
            if (!hash_equals($storedPassword, $password)) {
                return null;
            }

            $newHash = password_hash($password, PASSWORD_DEFAULT);
            if (is_string($newHash) && $newHash !== '') {
                $this->userRepository->updatePasswordHash($user->id, $newHash);
            }
        }

        return $user;
    }

    public function issueRememberToken(int $userId): string
    {
        $token   = bin2hex(random_bytes(32));
        $hash    = hash('sha256', $token);
        $days    = (int)\Core\Env::get('REMEMBER_ME_LIFETIME', 30);
        $expires = date('Y-m-d H:i:s', time() + $days * 86400);

        $this->userRepository->setRememberToken($userId, $hash, $expires);

        return $token;
    }

    public function validateRememberToken(string $cookieValue): ?User
    {
        $parts = explode(':', $cookieValue, 2);
        if (count($parts) !== 2) {
            return null;
        }

        [$rawId, $token] = $parts;
        $id = filter_var($rawId, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
        if ($id === false) {
            return null;
        }

        $user = $this->userRepository->findByIdWithRememberToken($id);
        if ($user === null || $user->rememberToken === null) {
            return null;
        }

        if (strtotime((string)$user->rememberTokenExpires) < time()) {
            $this->userRepository->clearRememberToken($id);
            return null;
        }

        if (!hash_equals($user->rememberToken, hash('sha256', $token))) {
            return null;
        }

        return $user;
    }

    public function revokeRememberToken(int $userId): void
    {
        $this->userRepository->clearRememberToken($userId);
    }
}
