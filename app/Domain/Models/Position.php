<?php

namespace App\Domain\Models;

class Position
{
    public function __construct(
        public readonly int $id,
        public readonly string $nombre,
    ) {}

    public static function fromRow(array $row): self
    {
        return new self(
            id: (int)($row['ID'] ?? 0),
            nombre: (string)($row['Nombredelpuesto'] ?? ''),
        );
    }

    public function toArray(): array
    {
        return [
            'ID'              => $this->id,
            'Nombredelpuesto' => $this->nombre,
        ];
    }
}
