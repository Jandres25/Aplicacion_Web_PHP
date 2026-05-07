<?php

namespace App\UseCases;

use App\Domain\Models\User;
use App\Http\Requests\Users\StoreUserRequest;
use App\Http\Requests\Users\UpdateUserRequest;
use App\Services\UserService;
use App\UseCases\DTOs\OperationResult;

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

    public function getUser(int $id): ?array
    {
        $user = $this->userService->getUser($id);
        return $user?->toArray();
    }

    public function createUser(StoreUserRequest $req): OperationResult
    {
        $result = $this->userService->createUser([
            'usuario'  => $req->usuario,
            'password' => $req->password,
            'correo'   => $req->correo,
        ]);
        return new OperationResult(
            (bool)($result['success'] ?? false),
            (string)($result['message'] ?? '')
        );
    }

    public function updateUser(UpdateUserRequest $req): OperationResult
    {
        $result = $this->userService->updateUser($req->id, [
            'usuario'  => $req->usuario,
            'password' => $req->password,
            'correo'   => $req->correo,
        ]);
        return new OperationResult(
            (bool)($result['success'] ?? false),
            (string)($result['message'] ?? '')
        );
    }

    public function deleteUser(int $id): bool
    {
        return $this->userService->deleteUser($id);
    }
}
