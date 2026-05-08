<?php

namespace Tests\Unit\Services;

use App\Domain\Contracts\UserRepositoryInterface;
use App\Domain\Models\User;
use App\Services\AuthService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class AuthServiceTest extends TestCase
{
    private UserRepositoryInterface&MockObject $repo;
    private AuthService $service;
    private string $passwordHash;

    protected function setUp(): void
    {
        $this->repo          = $this->createMock(UserRepositoryInterface::class);
        $this->service       = new AuthService($this->repo);
        $this->passwordHash  = password_hash('secret123', PASSWORD_DEFAULT);
    }

    private function makeUser(array $overrides = []): User
    {
        return User::fromRow(array_merge([
            'ID'             => '1',
            'Nombreusuario'  => 'admin',
            'Password'       => $this->passwordHash,
            'Correo'         => 'admin@example.com',
        ], $overrides));
    }

    // --- authenticate ---

    public function test_authenticate_returns_null_when_username_is_empty(): void
    {
        $this->repo->expects($this->never())->method('findByUsername');

        $this->assertNull($this->service->authenticate('', 'secret'));
    }

    public function test_authenticate_returns_null_when_password_is_empty(): void
    {
        $this->repo->expects($this->never())->method('findByUsername');

        $this->assertNull($this->service->authenticate('admin', ''));
    }

    public function test_authenticate_returns_null_when_user_not_found(): void
    {
        $this->repo->method('findByUsername')->willReturn(null);

        $this->assertNull($this->service->authenticate('admin', 'secret123'));
    }

    public function test_authenticate_returns_null_when_password_is_wrong(): void
    {
        $this->repo->method('findByUsername')->willReturn($this->makeUser());

        $this->assertNull($this->service->authenticate('admin', 'wrong-password'));
    }

    public function test_authenticate_returns_user_when_credentials_are_valid(): void
    {
        $user = $this->makeUser();
        $this->repo->method('findByUsername')->willReturn($user);

        $result = $this->service->authenticate('admin', 'secret123');

        $this->assertSame($user, $result);
    }

    public function test_authenticate_returns_null_when_stored_password_is_empty(): void
    {
        $user = $this->makeUser(['Password' => '']);
        $this->repo->method('findByUsername')->willReturn($user);

        $this->assertNull($this->service->authenticate('admin', 'secret123'));
    }

    public function test_authenticate_rehashes_plain_text_password_on_success(): void
    {
        // Simulates a legacy plain-text password stored in DB
        $user = $this->makeUser(['Password' => 'plain_secret']);
        $this->repo->method('findByUsername')->willReturn($user);

        $this->repo->expects($this->once())
            ->method('updatePasswordHash')
            ->with(1, $this->callback(fn($hash) => password_verify('plain_secret', $hash)));

        $result = $this->service->authenticate('admin', 'plain_secret');

        $this->assertSame($user, $result);
    }

    public function test_authenticate_trims_whitespace_from_credentials(): void
    {
        $this->repo->expects($this->once())
            ->method('findByUsername')
            ->with('admin')
            ->willReturn(null);

        $this->service->authenticate('  admin  ', '  secret  ');
    }

    // --- validateRememberToken ---

    public function test_validateRememberToken_returns_null_for_malformed_cookie(): void
    {
        $this->assertNull($this->service->validateRememberToken('no-colon-here'));
    }

    public function test_validateRememberToken_returns_null_for_invalid_user_id(): void
    {
        $this->assertNull($this->service->validateRememberToken('abc:sometoken'));
    }

    public function test_validateRememberToken_returns_null_when_user_not_found(): void
    {
        $this->repo->method('findByIdWithRememberToken')->willReturn(null);

        $this->assertNull($this->service->validateRememberToken('1:sometoken'));
    }

    public function test_validateRememberToken_returns_null_when_token_is_null(): void
    {
        $user = User::fromRow([
            'ID' => '1', 'Nombreusuario' => 'admin', 'Password' => null, 'Correo' => null,
            'remember_token' => null, 'remember_token_expires' => null,
        ]);
        $this->repo->method('findByIdWithRememberToken')->willReturn($user);

        $this->assertNull($this->service->validateRememberToken('1:sometoken'));
    }

    public function test_validateRememberToken_returns_null_when_token_is_expired(): void
    {
        $token = bin2hex(random_bytes(32));
        $user = User::fromRow([
            'ID' => '1', 'Nombreusuario' => 'admin', 'Password' => null, 'Correo' => null,
            'remember_token'         => hash('sha256', $token),
            'remember_token_expires' => date('Y-m-d H:i:s', time() - 1),
        ]);
        $this->repo->method('findByIdWithRememberToken')->willReturn($user);
        $this->repo->expects($this->once())->method('clearRememberToken')->with(1);

        $this->assertNull($this->service->validateRememberToken("1:{$token}"));
    }

    public function test_validateRememberToken_returns_null_when_token_hash_does_not_match(): void
    {
        $user = User::fromRow([
            'ID' => '1', 'Nombreusuario' => 'admin', 'Password' => null, 'Correo' => null,
            'remember_token'         => hash('sha256', 'correct_token'),
            'remember_token_expires' => date('Y-m-d H:i:s', time() + 3600),
        ]);
        $this->repo->method('findByIdWithRememberToken')->willReturn($user);

        $this->assertNull($this->service->validateRememberToken('1:wrong_token'));
    }

    public function test_validateRememberToken_returns_user_when_token_is_valid(): void
    {
        $token = bin2hex(random_bytes(32));
        $user = User::fromRow([
            'ID' => '1', 'Nombreusuario' => 'admin', 'Password' => null, 'Correo' => null,
            'remember_token'         => hash('sha256', $token),
            'remember_token_expires' => date('Y-m-d H:i:s', time() + 3600),
        ]);
        $this->repo->method('findByIdWithRememberToken')->willReturn($user);

        $result = $this->service->validateRememberToken("1:{$token}");

        $this->assertSame($user, $result);
    }

    // --- revokeRememberToken ---

    public function test_revokeRememberToken_delegates_to_repository(): void
    {
        $this->repo->expects($this->once())->method('clearRememberToken')->with(7);

        $this->service->revokeRememberToken(7);
    }
}
