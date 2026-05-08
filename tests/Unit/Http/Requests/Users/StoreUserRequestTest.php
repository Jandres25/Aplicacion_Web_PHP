<?php

namespace Tests\Unit\Http\Requests\Users;

use App\Http\Requests\Users\StoreUserRequest;
use PHPUnit\Framework\TestCase;

class StoreUserRequestTest extends TestCase
{
    private function validData(): array
    {
        return [
            'usuario'  => 'jdoe',
            'password' => 'secret123',
            'correo'   => 'jdoe@example.com',
        ];
    }

    public function test_fromArray_populates_all_fields(): void
    {
        $req = StoreUserRequest::fromArray($this->validData());

        $this->assertSame('jdoe', $req->usuario);
        $this->assertSame('secret123', $req->password);
        $this->assertSame('jdoe@example.com', $req->correo);
    }

    public function test_fromArray_trims_whitespace(): void
    {
        $req = StoreUserRequest::fromArray(['usuario' => '  jdoe  ', 'password' => 'secret123', 'correo' => ' jdoe@example.com ']);

        $this->assertSame('jdoe', $req->usuario);
        $this->assertSame('jdoe@example.com', $req->correo);
    }

    public function test_validate_returns_empty_array_when_all_fields_valid(): void
    {
        $errors = StoreUserRequest::fromArray($this->validData())->validate();

        $this->assertSame([], $errors);
    }

    public function test_validate_flags_empty_usuario(): void
    {
        $errors = StoreUserRequest::fromArray(array_merge($this->validData(), ['usuario' => '']))->validate();

        $this->assertArrayHasKey('usuario', $errors);
    }

    public function test_validate_flags_empty_password(): void
    {
        $errors = StoreUserRequest::fromArray(array_merge($this->validData(), ['password' => '']))->validate();

        $this->assertArrayHasKey('password', $errors);
    }

    public function test_validate_flags_password_shorter_than_8_chars(): void
    {
        $errors = StoreUserRequest::fromArray(array_merge($this->validData(), ['password' => '1234567']))->validate();

        $this->assertArrayHasKey('password', $errors);
    }

    public function test_validate_accepts_password_of_exactly_8_chars(): void
    {
        $errors = StoreUserRequest::fromArray(array_merge($this->validData(), ['password' => '12345678']))->validate();

        $this->assertArrayNotHasKey('password', $errors);
    }

    public function test_validate_flags_empty_correo(): void
    {
        $errors = StoreUserRequest::fromArray(array_merge($this->validData(), ['correo' => '']))->validate();

        $this->assertArrayHasKey('correo', $errors);
    }

    public function test_validate_flags_invalid_correo_format(): void
    {
        $errors = StoreUserRequest::fromArray(array_merge($this->validData(), ['correo' => 'no-es-email']))->validate();

        $this->assertArrayHasKey('correo', $errors);
    }
}
