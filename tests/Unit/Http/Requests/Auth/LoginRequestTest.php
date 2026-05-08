<?php

namespace Tests\Unit\Http\Requests\Auth;

use App\Http\Requests\Auth\LoginRequest;
use PHPUnit\Framework\TestCase;

class LoginRequestTest extends TestCase
{
    public function test_fromArray_populates_all_fields(): void
    {
        $req = LoginRequest::fromArray(['usuario' => 'admin', 'password' => 'secret', 'remember' => '1']);

        $this->assertSame('admin', $req->usuario);
        $this->assertSame('secret', $req->password);
        $this->assertTrue($req->remember);
    }

    public function test_fromArray_remember_is_false_when_missing(): void
    {
        $req = LoginRequest::fromArray(['usuario' => 'admin', 'password' => 'secret']);

        $this->assertFalse($req->remember);
    }

    public function test_fromArray_trims_usuario(): void
    {
        $req = LoginRequest::fromArray(['usuario' => '  admin  ', 'password' => 'x']);

        $this->assertSame('admin', $req->usuario);
    }

    public function test_validate_returns_empty_array_when_valid(): void
    {
        $errors = LoginRequest::fromArray(['usuario' => 'admin', 'password' => 'secret'])->validate();

        $this->assertSame([], $errors);
    }

    public function test_validate_flags_empty_usuario(): void
    {
        $errors = LoginRequest::fromArray(['usuario' => '', 'password' => 'secret'])->validate();

        $this->assertArrayHasKey('usuario', $errors);
    }

    public function test_validate_flags_empty_password(): void
    {
        $errors = LoginRequest::fromArray(['usuario' => 'admin', 'password' => ''])->validate();

        $this->assertArrayHasKey('password', $errors);
    }

    public function test_validate_flags_both_fields_empty(): void
    {
        $errors = LoginRequest::fromArray(['usuario' => '', 'password' => ''])->validate();

        $this->assertArrayHasKey('usuario', $errors);
        $this->assertArrayHasKey('password', $errors);
    }
}
