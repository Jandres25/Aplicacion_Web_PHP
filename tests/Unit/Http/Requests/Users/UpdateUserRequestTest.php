<?php

namespace Tests\Unit\Http\Requests\Users;

use App\Http\Requests\Users\UpdateUserRequest;
use PHPUnit\Framework\TestCase;

class UpdateUserRequestTest extends TestCase
{
    private function validData(): array
    {
        return [
            'txtID'    => '3',
            'usuario'  => 'jdoe',
            'password' => '',
            'correo'   => 'jdoe@example.com',
        ];
    }

    public function test_fromArray_populates_all_fields(): void
    {
        $req = UpdateUserRequest::fromArray($this->validData());

        $this->assertSame(3, $req->id);
        $this->assertSame('jdoe', $req->usuario);
        $this->assertSame('', $req->password);
        $this->assertSame('jdoe@example.com', $req->correo);
    }

    public function test_validate_returns_empty_array_when_valid(): void
    {
        $errors = UpdateUserRequest::fromArray($this->validData())->validate();

        $this->assertSame([], $errors);
    }

    public function test_validate_allows_empty_password_on_update(): void
    {
        $errors = UpdateUserRequest::fromArray(array_merge($this->validData(), ['password' => '']))->validate();

        $this->assertArrayNotHasKey('password', $errors);
    }

    public function test_validate_flags_password_shorter_than_8_when_provided(): void
    {
        $errors = UpdateUserRequest::fromArray(array_merge($this->validData(), ['password' => '1234567']))->validate();

        $this->assertArrayHasKey('password', $errors);
    }

    public function test_validate_flags_id_zero(): void
    {
        $errors = UpdateUserRequest::fromArray(array_merge($this->validData(), ['txtID' => '0']))->validate();

        $this->assertArrayHasKey('txtID', $errors);
    }

    public function test_validate_flags_empty_usuario(): void
    {
        $errors = UpdateUserRequest::fromArray(array_merge($this->validData(), ['usuario' => '']))->validate();

        $this->assertArrayHasKey('usuario', $errors);
    }

    public function test_validate_flags_invalid_correo_format(): void
    {
        $errors = UpdateUserRequest::fromArray(array_merge($this->validData(), ['correo' => 'invalido']))->validate();

        $this->assertArrayHasKey('correo', $errors);
    }
}
