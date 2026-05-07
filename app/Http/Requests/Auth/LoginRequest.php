<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\Request;

class LoginRequest extends Request
{
    public function __construct(
        public readonly string $usuario,
        public readonly string $password,
        public readonly bool   $remember
    ) {}

    public static function fromArray(array $data): static
    {
        return new static(
            self::str($data, 'usuario'),
            self::str($data, 'password'),
            self::bool($data, 'remember')
        );
    }

    public function validate(): array
    {
        $errors = [];
        if ($this->usuario === '') {
            $errors['usuario'] = 'El usuario es obligatorio.';
        }
        if ($this->password === '') {
            $errors['password'] = 'La contraseña es obligatoria.';
        }
        return $errors;
    }
}
