<?php

namespace Tests\Unit\Http\Requests\Employees;

use App\Http\Requests\Employees\UpdateEmployeeRequest;
use PHPUnit\Framework\TestCase;

class UpdateEmployeeRequestTest extends TestCase
{
    private function validData(): array
    {
        return [
            'txtID'           => '5',
            'primernombre'    => 'María',
            'segundonombre'   => '',
            'primerapellido'  => 'García',
            'segundoapellido' => 'Martínez',
            'idpuesto'        => '2',
            'fechadeingreso'  => '2023-06-01',
        ];
    }

    public function test_fromArray_populates_all_fields(): void
    {
        $req = UpdateEmployeeRequest::fromArray($this->validData());

        $this->assertSame(5, $req->id);
        $this->assertSame('María', $req->primerNombre);
        $this->assertNull($req->segundoNombre);
        $this->assertSame('García', $req->primerApellido);
        $this->assertSame('Martínez', $req->segundoApellido);
        $this->assertSame(2, $req->idPuesto);
        $this->assertSame('2023-06-01', $req->fechaIngreso);
    }

    public function test_validate_returns_empty_array_when_all_fields_valid(): void
    {
        $errors = UpdateEmployeeRequest::fromArray($this->validData())->validate();

        $this->assertSame([], $errors);
    }

    public function test_validate_flags_invalid_id_zero(): void
    {
        $errors = UpdateEmployeeRequest::fromArray(array_merge($this->validData(), ['txtID' => '0']))->validate();

        $this->assertArrayHasKey('txtID', $errors);
    }

    public function test_validate_flags_invalid_id_negative(): void
    {
        $errors = UpdateEmployeeRequest::fromArray(array_merge($this->validData(), ['txtID' => '-3']))->validate();

        $this->assertArrayHasKey('txtID', $errors);
    }

    public function test_validate_flags_missing_primer_nombre(): void
    {
        $errors = UpdateEmployeeRequest::fromArray(array_merge($this->validData(), ['primernombre' => '']))->validate();

        $this->assertArrayHasKey('primernombre', $errors);
    }

    public function test_validate_flags_invalid_date(): void
    {
        $errors = UpdateEmployeeRequest::fromArray(array_merge($this->validData(), ['fechadeingreso' => '31/12/2023']))->validate();

        $this->assertArrayHasKey('fechadeingreso', $errors);
    }
}
