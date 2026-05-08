<?php

namespace Tests\Unit\Http\Requests\Positions;

use App\Http\Requests\Positions\StorePositionRequest;
use PHPUnit\Framework\TestCase;

class StorePositionRequestTest extends TestCase
{
    public function test_fromArray_populates_nombre(): void
    {
        $req = StorePositionRequest::fromArray(['nombredelpuesto' => 'Gerente']);

        $this->assertSame('Gerente', $req->nombre);
    }

    public function test_fromArray_trims_whitespace(): void
    {
        $req = StorePositionRequest::fromArray(['nombredelpuesto' => '  Analista  ']);

        $this->assertSame('Analista', $req->nombre);
    }

    public function test_fromArray_returns_empty_string_when_missing(): void
    {
        $req = StorePositionRequest::fromArray([]);

        $this->assertSame('', $req->nombre);
    }

    public function test_validate_returns_empty_array_when_valid(): void
    {
        $errors = StorePositionRequest::fromArray(['nombredelpuesto' => 'Contador'])->validate();

        $this->assertSame([], $errors);
    }

    public function test_validate_flags_empty_nombre(): void
    {
        $errors = StorePositionRequest::fromArray(['nombredelpuesto' => ''])->validate();

        $this->assertArrayHasKey('nombredelpuesto', $errors);
    }

    public function test_validate_flags_missing_nombre(): void
    {
        $errors = StorePositionRequest::fromArray([])->validate();

        $this->assertArrayHasKey('nombredelpuesto', $errors);
    }
}
