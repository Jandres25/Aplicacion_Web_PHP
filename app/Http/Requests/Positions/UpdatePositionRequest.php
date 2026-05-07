<?php

namespace App\Http\Requests\Positions;

use App\Http\Requests\Request;

class UpdatePositionRequest extends Request
{
    public function __construct(
        public readonly int    $id,
        public readonly string $nombre
    ) {}

    public static function fromArray(array $data): static
    {
        return new static(
            self::int($data, 'txtID'),
            self::str($data, 'nombredelpuesto')
        );
    }

    public function validate(): array
    {
        $errors = [];
        if ($this->id <= 0) {
            $errors['txtID'] = 'El ID del puesto no es válido.';
        }
        if ($this->nombre === '') {
            $errors['nombredelpuesto'] = 'Debe ingresar el nombre del puesto.';
        }
        return $errors;
    }
}
