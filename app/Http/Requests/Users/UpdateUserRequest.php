<?php

namespace App\Http\Requests\Users;

use App\Http\Requests\Request;

class UpdateUserRequest extends Request
{
    public function __construct(
        public readonly int    $id,
        public readonly string $usuario,
        public readonly string $password,
        public readonly string $correo
    ) {}

    public static function fromArray(array $data): static
    {
        return new static(
            self::int($data, 'txtID'),
            self::str($data, 'usuario'),
            self::str($data, 'password'),
            self::str($data, 'correo')
        );
    }

    public function validate(): array
    {
        $errors = [];
        if ($this->id <= 0) {
            $errors['txtID'] = 'El ID del usuario no es válido.';
        }
        if ($this->usuario === '') {
            $errors['usuario'] = 'El usuario es obligatorio.';
        }
        if ($this->password !== '' && strlen($this->password) < 8) {
            $errors['password'] = 'La contraseña debe tener al menos 8 caracteres.';
        }
        if ($this->correo === '') {
            $errors['correo'] = 'El correo es obligatorio.';
        } elseif (!filter_var($this->correo, FILTER_VALIDATE_EMAIL)) {
            $errors['correo'] = 'Debe ingresar un correo válido.';
        }
        return $errors;
    }
}
