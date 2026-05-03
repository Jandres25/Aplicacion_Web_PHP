<?php

namespace App\UseCases;

use App\Repositories\UserRepository;
use App\Services\UserService;
use Config\Database;

class UserUseCase
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public static function fromEnvironment(): self
    {
        $repository = new UserRepository(Database::getConnection());
        $service = new UserService($repository);
        return new self($service);
    }

    public function listUsers()
    {
        return $this->userService->listUsers();
    }

    public function getUser($id)
    {
        return $this->userService->getUser($id);
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
