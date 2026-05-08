<?php

namespace Tests\Unit\Domain\Models;

use App\Domain\Models\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    private function fullRow(): array
    {
        return [
            'ID'                    => '2',
            'Nombreusuario'         => 'admin',
            'Password'              => '$2y$10$hash',
            'Correo'                => 'admin@example.com',
            'remember_token'        => 'abc123',
            'remember_token_expires'=> '2026-06-01 00:00:00',
        ];
    }

    public function test_fromRow_maps_full_row_with_all_fields(): void
    {
        $user = User::fromRow($this->fullRow());

        $this->assertSame(2, $user->id);
        $this->assertSame('admin', $user->usuario);
        $this->assertSame('$2y$10$hash', $user->password);
        $this->assertSame('admin@example.com', $user->correo);
        $this->assertSame('abc123', $user->rememberToken);
        $this->assertSame('2026-06-01 00:00:00', $user->rememberTokenExpires);
    }

    public function test_fromRow_casts_id_to_int(): void
    {
        $user = User::fromRow($this->fullRow());

        $this->assertIsInt($user->id);
    }

    public function test_fromRow_returns_null_for_empty_password(): void
    {
        $row = array_merge($this->fullRow(), ['Password' => '']);
        $user = User::fromRow($row);

        $this->assertNull($user->password);
    }

    public function test_fromRow_returns_null_for_empty_correo(): void
    {
        $row = array_merge($this->fullRow(), ['Correo' => '']);
        $user = User::fromRow($row);

        $this->assertNull($user->correo);
    }

    public function test_fromRow_returns_null_for_missing_remember_token(): void
    {
        $row = $this->fullRow();
        unset($row['remember_token'], $row['remember_token_expires']);
        $user = User::fromRow($row);

        $this->assertNull($user->rememberToken);
        $this->assertNull($user->rememberTokenExpires);
    }

    public function test_fromRow_defaults_when_row_is_empty(): void
    {
        $user = User::fromRow([]);

        $this->assertSame(0, $user->id);
        $this->assertSame('', $user->usuario);
        $this->assertNull($user->password);
        $this->assertNull($user->correo);
        $this->assertNull($user->rememberToken);
        $this->assertNull($user->rememberTokenExpires);
    }

    public function test_toArray_omits_password_and_remember_fields(): void
    {
        $array = User::fromRow($this->fullRow())->toArray();

        $this->assertArrayNotHasKey('Password', $array);
        $this->assertArrayNotHasKey('remember_token', $array);
        $this->assertArrayNotHasKey('remember_token_expires', $array);
    }

    public function test_toArray_returns_correct_keys_and_values(): void
    {
        $array = User::fromRow($this->fullRow())->toArray();

        $this->assertSame(2, $array['ID']);
        $this->assertSame('admin', $array['Nombreusuario']);
        $this->assertSame('admin@example.com', $array['Correo']);
    }

    public function test_toArray_replaces_null_correo_with_empty_string(): void
    {
        $row = array_merge($this->fullRow(), ['Correo' => '']);
        $array = User::fromRow($row)->toArray();

        $this->assertSame('', $array['Correo']);
    }
}
