<?php

namespace Tests\Unit\Http\Requests\Positions;

use App\Http\Requests\Positions\UpdatePositionRequest;
use PHPUnit\Framework\TestCase;

class UpdatePositionRequestTest extends TestCase
{
    private function validData(): array
    {
        return ['txtID' => '4', 'nombredelpuesto' => 'Director'];
    }

    public function test_fromArray_populates_all_fields(): void
    {
        $req = UpdatePositionRequest::fromArray($this->validData());

        $this->assertSame(4, $req->id);
        $this->assertSame('Director', $req->nombre);
    }

    public function test_validate_returns_empty_array_when_valid(): void
    {
        $errors = UpdatePositionRequest::fromArray($this->validData())->validate();

        $this->assertSame([], $errors);
    }

    public function test_validate_flags_id_zero(): void
    {
        $errors = UpdatePositionRequest::fromArray(['txtID' => '0', 'nombredelpuesto' => 'X'])->validate();

        $this->assertArrayHasKey('txtID', $errors);
    }

    public function test_validate_flags_id_negative(): void
    {
        $errors = UpdatePositionRequest::fromArray(['txtID' => '-1', 'nombredelpuesto' => 'X'])->validate();

        $this->assertArrayHasKey('txtID', $errors);
    }

    public function test_validate_flags_empty_nombre(): void
    {
        $errors = UpdatePositionRequest::fromArray(['txtID' => '1', 'nombredelpuesto' => ''])->validate();

        $this->assertArrayHasKey('nombredelpuesto', $errors);
    }
}
