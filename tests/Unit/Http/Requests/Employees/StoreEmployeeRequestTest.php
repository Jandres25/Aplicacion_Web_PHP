<?php

namespace Tests\Unit\Http\Requests\Employees;

use App\Http\Requests\Employees\StoreEmployeeRequest;
use PHPUnit\Framework\TestCase;

class StoreEmployeeRequestTest extends TestCase
{
    private function validData(): array
    {
        return [
            'primernombre'    => 'Juan',
            'segundonombre'   => 'Carlos',
            'primerapellido'  => 'Pérez',
            'segundoapellido' => 'López',
            'idpuesto'        => '3',
            'fechadeingreso'  => '2024-01-15',
        ];
    }

    public function test_fromArray_populates_all_fields(): void
    {
        $req = StoreEmployeeRequest::fromArray($this->validData());

        $this->assertSame('Juan', $req->primerNombre);
        $this->assertSame('Carlos', $req->segundoNombre);
        $this->assertSame('Pérez', $req->primerApellido);
        $this->assertSame('López', $req->segundoApellido);
        $this->assertSame(3, $req->idPuesto);
        $this->assertSame('2024-01-15', $req->fechaIngreso);
    }

    public function test_fromArray_trims_whitespace_on_string_fields(): void
    {
        $data = array_merge($this->validData(), [
            'primernombre'   => '  Juan  ',
            'primerapellido' => '  Pérez  ',
        ]);
        $req = StoreEmployeeRequest::fromArray($data);

        $this->assertSame('Juan', $req->primerNombre);
        $this->assertSame('Pérez', $req->primerApellido);
    }

    public function test_fromArray_returns_null_for_empty_segundo_nombre(): void
    {
        $req = StoreEmployeeRequest::fromArray(array_merge($this->validData(), ['segundonombre' => '']));

        $this->assertNull($req->segundoNombre);
    }

    public function test_fromArray_returns_null_when_segundo_nombre_missing(): void
    {
        $data = $this->validData();
        unset($data['segundonombre']);
        $req = StoreEmployeeRequest::fromArray($data);

        $this->assertNull($req->segundoNombre);
    }

    public function test_fromArray_casts_idpuesto_to_int(): void
    {
        $req = StoreEmployeeRequest::fromArray($this->validData());

        $this->assertIsInt($req->idPuesto);
        $this->assertSame(3, $req->idPuesto);
    }

    public function test_validate_returns_empty_array_when_all_fields_valid(): void
    {
        $errors = StoreEmployeeRequest::fromArray($this->validData())->validate();

        $this->assertSame([], $errors);
    }

    public function test_validate_flags_missing_primer_nombre(): void
    {
        $errors = StoreEmployeeRequest::fromArray(array_merge($this->validData(), ['primernombre' => '']))->validate();

        $this->assertArrayHasKey('primernombre', $errors);
    }

    public function test_validate_flags_missing_primer_apellido(): void
    {
        $errors = StoreEmployeeRequest::fromArray(array_merge($this->validData(), ['primerapellido' => '']))->validate();

        $this->assertArrayHasKey('primerapellido', $errors);
    }

    public function test_validate_flags_missing_segundo_apellido(): void
    {
        $errors = StoreEmployeeRequest::fromArray(array_merge($this->validData(), ['segundoapellido' => '']))->validate();

        $this->assertArrayHasKey('segundoapellido', $errors);
    }

    public function test_validate_flags_idpuesto_zero(): void
    {
        $errors = StoreEmployeeRequest::fromArray(array_merge($this->validData(), ['idpuesto' => '0']))->validate();

        $this->assertArrayHasKey('idpuesto', $errors);
    }

    public function test_validate_flags_idpuesto_negative(): void
    {
        $errors = StoreEmployeeRequest::fromArray(array_merge($this->validData(), ['idpuesto' => '-1']))->validate();

        $this->assertArrayHasKey('idpuesto', $errors);
    }

    public function test_validate_flags_empty_fecha(): void
    {
        $errors = StoreEmployeeRequest::fromArray(array_merge($this->validData(), ['fechadeingreso' => '']))->validate();

        $this->assertArrayHasKey('fechadeingreso', $errors);
    }

    /**
     * @dataProvider invalidDateProvider
     */
    public function test_validate_flags_invalid_date_format(string $date): void
    {
        $errors = StoreEmployeeRequest::fromArray(array_merge($this->validData(), ['fechadeingreso' => $date]))->validate();

        $this->assertArrayHasKey('fechadeingreso', $errors);
    }

    public static function invalidDateProvider(): array
    {
        return [
            'wrong format dd/mm/yyyy' => ['15/01/2024'],
            'wrong format mm-dd-yyyy' => ['01-15-2024'],
            'invalid month 13'        => ['2024-13-01'],
            'invalid day 40'          => ['2024-01-40'],
            'plain text'              => ['no-es-fecha'],
        ];
    }

    public function test_validate_accepts_valid_iso_date(): void
    {
        $errors = StoreEmployeeRequest::fromArray($this->validData())->validate();

        $this->assertArrayNotHasKey('fechadeingreso', $errors);
    }
}
