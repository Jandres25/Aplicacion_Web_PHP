<?php

namespace Tests\Unit\Domain\Models;

use App\Domain\Models\Position;
use PHPUnit\Framework\TestCase;

class PositionTest extends TestCase
{
    public function test_fromRow_maps_row_correctly(): void
    {
        $position = Position::fromRow(['ID' => '7', 'Nombredelpuesto' => 'Gerente General']);

        $this->assertSame(7, $position->id);
        $this->assertSame('Gerente General', $position->nombre);
    }

    public function test_fromRow_casts_id_to_int(): void
    {
        $position = Position::fromRow(['ID' => '42', 'Nombredelpuesto' => 'Analista']);

        $this->assertIsInt($position->id);
    }

    public function test_fromRow_defaults_when_row_is_empty(): void
    {
        $position = Position::fromRow([]);

        $this->assertSame(0, $position->id);
        $this->assertSame('', $position->nombre);
    }

    public function test_toArray_returns_correct_keys_and_values(): void
    {
        $position = Position::fromRow(['ID' => '3', 'Nombredelpuesto' => 'Contador']);
        $array = $position->toArray();

        $this->assertSame(3, $array['ID']);
        $this->assertSame('Contador', $array['Nombredelpuesto']);
    }

    public function test_toArray_roundtrip_preserves_data(): void
    {
        $row = ['ID' => 10, 'Nombredelpuesto' => 'Director'];
        $array = Position::fromRow($row)->toArray();

        $this->assertSame(10, $array['ID']);
        $this->assertSame('Director', $array['Nombredelpuesto']);
    }
}
