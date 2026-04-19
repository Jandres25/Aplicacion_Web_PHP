<?php

namespace App\Services;

use App\Repositories\UserRepository;
use PDOException;

class UserService
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function listUsers()
    {
        return $this->userRepository->listAll();
    }

    public function getUser($id)
    {
        $userId = (int)$id;
        if ($userId <= 0) {
            return null;
        }
        return $this->userRepository->findById($userId);
    }

    public function createUser($data)
    {
        $validationError = $this->validateUserData($data, false);
        if ($validationError !== null) {
            return ['success' => false, 'message' => $validationError];
        }

        $rawPassword = trim((string)($data['password'] ?? ''));
        $passwordHash = password_hash($rawPassword, PASSWORD_DEFAULT);
        if (!is_string($passwordHash) || $passwordHash === '') {
            return ['success' => false, 'message' => 'No se pudo procesar la contraseña.'];
        }

        try {
            $created = $this->userRepository->create([
                'Nombreusuario' => trim((string)($data['usuario'] ?? '')),
                'Password' => $passwordHash,
                'Correo' => trim((string)($data['correo'] ?? ''))
            ]);
        } catch (PDOException $exception) {
            return ['success' => false, 'message' => 'No se pudo agregar el registro.'];
        }

        if (!$created) {
            return ['success' => false, 'message' => 'No se pudo agregar el registro.'];
        }

        return ['success' => true, 'message' => 'Registro agregado'];
    }

    public function updateUser($id, $data)
    {
        $userId = (int)$id;
        if ($userId <= 0) {
            return ['success' => false, 'message' => 'El ID del usuario no es válido.'];
        }

        $validationError = $this->validateUserData($data, true);
        if ($validationError !== null) {
            return ['success' => false, 'message' => $validationError];
        }

        $existingUser = $this->userRepository->findById($userId);
        if ($existingUser === null) {
            return ['success' => false, 'message' => 'No se encontró el usuario a editar.'];
        }

        $rawPassword = trim((string)($data['password'] ?? ''));
        $passwordToPersist = (string)($existingUser['Password'] ?? '');
        if ($rawPassword !== '') {
            $passwordHash = password_hash($rawPassword, PASSWORD_DEFAULT);
            if (!is_string($passwordHash) || $passwordHash === '') {
                return ['success' => false, 'message' => 'No se pudo procesar la contraseña.'];
            }
            $passwordToPersist = $passwordHash;
        }

        try {
            $updated = $this->userRepository->update($userId, [
                'Nombreusuario' => trim((string)($data['usuario'] ?? '')),
                'Password' => $passwordToPersist,
                'Correo' => trim((string)($data['correo'] ?? ''))
            ]);
        } catch (PDOException $exception) {
            return ['success' => false, 'message' => 'No se pudo actualizar el registro.'];
        }

        if (!$updated) {
            return ['success' => false, 'message' => 'No se pudo actualizar el registro.'];
        }

        return ['success' => true, 'message' => 'Registro actualizado'];
    }

    public function deleteUser($id)
    {
        $userId = (int)$id;
        if ($userId <= 0) {
            return false;
        }
        return $this->userRepository->deleteById($userId);
    }

    private function validateUserData($data, $isUpdate = false)
    {
        $usuario = trim((string)($data['usuario'] ?? ''));
        $password = trim((string)($data['password'] ?? ''));
        $correo = trim((string)($data['correo'] ?? ''));

        if ($usuario === '' || $correo === '') {
            return 'Debe completar los campos obligatorios del usuario y correo.';
        }

        if (!$isUpdate && $password === '') {
            return 'Debe completar la contraseña del usuario.';
        }

        if ($password !== '' && strlen($password) < 8) {
            return 'La contraseña debe tener al menos 8 caracteres.';
        }

        if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            return 'Debe ingresar un correo válido.';
        }

        return null;
    }
}
