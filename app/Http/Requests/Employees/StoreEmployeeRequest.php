<?php

namespace App\Http\Requests\Employees;

use App\Http\Requests\Request;

class StoreEmployeeRequest extends Request
{
    public function __construct(
        public readonly string  $primerNombre,
        public readonly ?string $segundoNombre,
        public readonly string  $primerApellido,
        public readonly string  $segundoApellido,
        public readonly int     $idPuesto,
        public readonly string  $fechaIngreso
    ) {}

    public static function fromArray(array $data): static
    {
        return new static(
            self::str($data, 'primernombre'),
            self::nullableStr($data, 'segundonombre'),
            self::str($data, 'primerapellido'),
            self::str($data, 'segundoapellido'),
            self::int($data, 'idpuesto'),
            self::str($data, 'fechadeingreso')
        );
    }

    public function validate(): array
    {
        $errors = [];
        if ($this->primerNombre === '') {
            $errors['primernombre'] = 'El primer nombre es obligatorio.';
        }
        if ($this->primerApellido === '') {
            $errors['primerapellido'] = 'El primer apellido es obligatorio.';
        }
        if ($this->segundoApellido === '') {
            $errors['segundoapellido'] = 'El segundo apellido es obligatorio.';
        }
        if ($this->idPuesto <= 0) {
            $errors['idpuesto'] = 'Debe seleccionar un puesto válido.';
        }
        if ($this->fechaIngreso === '' || !$this->isValidDate($this->fechaIngreso)) {
            $errors['fechadeingreso'] = 'Debe seleccionar una fecha de ingreso válida.';
        }
        return $errors;
    }

    private function isValidDate(string $date): bool
    {
        $parsed = \DateTime::createFromFormat('Y-m-d', $date);
        return $parsed !== false && $parsed->format('Y-m-d') === $date;
    }
}
