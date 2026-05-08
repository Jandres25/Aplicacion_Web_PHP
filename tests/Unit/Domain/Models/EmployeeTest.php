<?php

namespace Tests\Unit\Domain\Models;

use App\Domain\Models\Employee;
use PHPUnit\Framework\TestCase;

class EmployeeTest extends TestCase
{
    private function fullRow(): array
    {
        return [
            'ID'              => '5',
            'Primernombre'    => 'Juan',
            'Segundonombre'   => 'Carlos',
            'Primerapellido'  => 'Pérez',
            'Segundoapellido' => 'López',
            'Foto'            => 'foto.jpg',
            'CV'              => 'cv.pdf',
            'Idpuesto'        => '3',
            'Fecha'           => '2024-01-15',
            'puesto'          => 'Gerente',
        ];
    }

    public function test_fromRow_maps_full_row_with_all_fields(): void
    {
        $employee = Employee::fromRow($this->fullRow());

        $this->assertSame(5, $employee->id);
        $this->assertSame('Juan', $employee->primerNombre);
        $this->assertSame('Carlos', $employee->segundoNombre);
        $this->assertSame('Pérez', $employee->primerApellido);
        $this->assertSame('López', $employee->segundoApellido);
        $this->assertSame('foto.jpg', $employee->foto);
        $this->assertSame('cv.pdf', $employee->cv);
        $this->assertSame(3, $employee->idPuesto);
        $this->assertSame('2024-01-15', $employee->fecha);
        $this->assertSame('Gerente', $employee->puestoNombre);
    }

    public function test_fromRow_casts_id_and_idPuesto_to_int(): void
    {
        $employee = Employee::fromRow($this->fullRow());

        $this->assertIsInt($employee->id);
        $this->assertIsInt($employee->idPuesto);
    }

    public function test_fromRow_returns_null_for_empty_segundo_nombre(): void
    {
        $row = array_merge($this->fullRow(), ['Segundonombre' => '']);
        $employee = Employee::fromRow($row);

        $this->assertNull($employee->segundoNombre);
    }

    public function test_fromRow_returns_null_when_segundo_nombre_missing(): void
    {
        $row = $this->fullRow();
        unset($row['Segundonombre']);
        $employee = Employee::fromRow($row);

        $this->assertNull($employee->segundoNombre);
    }

    public function test_fromRow_returns_null_for_empty_foto_and_cv(): void
    {
        $row = array_merge($this->fullRow(), ['Foto' => '', 'CV' => '']);
        $employee = Employee::fromRow($row);

        $this->assertNull($employee->foto);
        $this->assertNull($employee->cv);
    }

    public function test_fromRow_returns_null_when_foto_and_cv_missing(): void
    {
        $row = $this->fullRow();
        unset($row['Foto'], $row['CV']);
        $employee = Employee::fromRow($row);

        $this->assertNull($employee->foto);
        $this->assertNull($employee->cv);
    }

    public function test_fromRow_returns_null_for_empty_puesto_nombre(): void
    {
        $row = array_merge($this->fullRow(), ['puesto' => '']);
        $employee = Employee::fromRow($row);

        $this->assertNull($employee->puestoNombre);
    }

    public function test_fromRow_defaults_when_row_is_empty(): void
    {
        $employee = Employee::fromRow([]);

        $this->assertSame(0, $employee->id);
        $this->assertSame('', $employee->primerNombre);
        $this->assertSame('', $employee->primerApellido);
        $this->assertSame('', $employee->segundoApellido);
        $this->assertSame(0, $employee->idPuesto);
        $this->assertSame('', $employee->fecha);
        $this->assertNull($employee->segundoNombre);
        $this->assertNull($employee->foto);
        $this->assertNull($employee->cv);
        $this->assertNull($employee->puestoNombre);
    }

    public function test_toArray_returns_correct_keys_and_values(): void
    {
        $employee = Employee::fromRow($this->fullRow());
        $array = $employee->toArray();

        $this->assertSame(5, $array['ID']);
        $this->assertSame('Juan', $array['Primernombre']);
        $this->assertSame('Carlos', $array['Segundonombre']);
        $this->assertSame('Pérez', $array['Primerapellido']);
        $this->assertSame('López', $array['Segundoapellido']);
        $this->assertSame('foto.jpg', $array['Foto']);
        $this->assertSame('cv.pdf', $array['CV']);
        $this->assertSame(3, $array['Idpuesto']);
        $this->assertSame('2024-01-15', $array['Fecha']);
        $this->assertSame('Gerente', $array['puesto']);
    }

    public function test_toArray_replaces_null_optional_fields_with_empty_string(): void
    {
        $row = array_merge($this->fullRow(), [
            'Segundonombre' => '',
            'Foto'          => '',
            'CV'            => '',
            'puesto'        => '',
        ]);
        $array = Employee::fromRow($row)->toArray();

        $this->assertSame('', $array['Segundonombre']);
        $this->assertSame('', $array['Foto']);
        $this->assertSame('', $array['CV']);
        $this->assertSame('', $array['puesto']);
    }
}
