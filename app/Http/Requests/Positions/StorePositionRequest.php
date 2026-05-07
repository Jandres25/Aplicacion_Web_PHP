<?php

namespace App\Http\Requests\Positions;

use App\Http\Requests\Request;

class StorePositionRequest extends Request
{
    public function __construct(
        public readonly string $nombre
    ) {}

    public static function fromArray(array $data): static
    {
        return new static(self::str($data, 'nombredelpuesto'));
    }

    public function validate(): array
    {
        $errors = [];
        if ($this->nombre === '') {
            $errors['nombredelpuesto'] = 'Debe ingresar el nombre del puesto.';
        }
        return $errors;
    }
}
