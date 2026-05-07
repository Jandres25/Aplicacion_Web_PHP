<?php

namespace App\UseCases;

use App\Domain\Models\User;
use App\Services\UserService;

class UserUseCase
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function listUsers(): array
    {
        return array_map(fn(User $u) => $u->toArray(), $this->userService->listUsers());
    }

    public function getUser($id): ?array
    {
        $user = $this->userService->getUser($id);
        return $user?->toArray();
    }

    public function createUser($data)
    {
        return $this->userService->createUser($data);
    }

    public function updateUser($id, $data)
    {
        return $this->userService->updateUser($id, $data);
    }

    public function deleteUser($id)
    {
        return $this->userService->deleteUser($id);
    }
}
