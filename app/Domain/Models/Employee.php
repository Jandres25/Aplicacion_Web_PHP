<?php

namespace App\Domain\Models;

class Employee
{
    public function __construct(
        public readonly int $id,
        public readonly string $primerNombre,
        public readonly ?string $segundoNombre,
        public readonly string $primerApellido,
        public readonly string $segundoApellido,
        public readonly ?string $foto,
        public readonly ?string $cv,
        public readonly int $idPuesto,
        public readonly string $fecha,
        public readonly ?string $puestoNombre = null,
    ) {}

    public static function fromRow(array $row): self
    {
        return new self(
            id: (int)($row['ID'] ?? 0),
            primerNombre: (string)($row['Primernombre'] ?? ''),
            segundoNombre: (isset($row['Segundonombre']) && $row['Segundonombre'] !== '') ? (string)$row['Segundonombre'] : null,
            primerApellido: (string)($row['Primerapellido'] ?? ''),
            segundoApellido: (string)($row['Segundoapellido'] ?? ''),
            foto: (isset($row['Foto']) && $row['Foto'] !== '') ? (string)$row['Foto'] : null,
            cv: (isset($row['CV']) && $row['CV'] !== '') ? (string)$row['CV'] : null,
            idPuesto: (int)($row['Idpuesto'] ?? 0),
            fecha: (string)($row['Fecha'] ?? ''),
            puestoNombre: (isset($row['puesto']) && $row['puesto'] !== '') ? (string)$row['puesto'] : null,
        );
    }

    public function toArray(): array
    {
        return [
            'ID'              => $this->id,
            'Primernombre'    => $this->primerNombre,
            'Segundonombre'   => $this->segundoNombre ?? '',
            'Primerapellido'  => $this->primerApellido,
            'Segundoapellido' => $this->segundoApellido,
            'Foto'            => $this->foto ?? '',
            'CV'              => $this->cv ?? '',
            'Idpuesto'        => $this->idPuesto,
            'Fecha'           => $this->fecha,
            'puesto'          => $this->puestoNombre ?? '',
        ];
    }
}
