<?php

namespace Tests\Unit\Services;

use App\Domain\Contracts\PositionRepositoryInterface;
use App\Domain\Models\Position;
use App\Services\PositionService;
use PDOException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class PositionServiceTest extends TestCase
{
    private PositionRepositoryInterface&MockObject $repo;
    private PositionService $service;

    protected function setUp(): void
    {
        $this->repo    = $this->createMock(PositionRepositoryInterface::class);
        $this->service = new PositionService($this->repo);
    }

    private function makePosition(int $id = 1, string $nombre = 'Gerente'): Position
    {
        return Position::fromRow(['ID' => $id, 'Nombredelpuesto' => $nombre]);
    }

    // --- listPositions ---

    public function test_listPositions_delegates_to_repository(): void
    {
        $positions = [$this->makePosition()];
        $this->repo->expects($this->once())->method('listAll')->willReturn($positions);

        $this->assertSame($positions, $this->service->listPositions());
    }

    // --- getPosition ---

    public function test_getPosition_returns_position_from_repository(): void
    {
        $position = $this->makePosition(3);
        $this->repo->method('findById')->with(3)->willReturn($position);

        $this->assertSame($position, $this->service->getPosition(3));
    }

    public function test_getPosition_returns_null_when_id_is_zero(): void
    {
        $this->repo->expects($this->never())->method('findById');

        $this->assertNull($this->service->getPosition(0));
    }

    public function test_getPosition_returns_null_when_id_is_negative(): void
    {
        $this->repo->expects($this->never())->method('findById');

        $this->assertNull($this->service->getPosition(-1));
    }

    public function test_getPosition_returns_null_when_not_found(): void
    {
        $this->repo->method('findById')->willReturn(null);

        $this->assertNull($this->service->getPosition(99));
    }

    // --- createPosition ---

    public function test_create_returns_failure_when_name_is_empty(): void
    {
        $this->repo->expects($this->never())->method('create');

        $result = $this->service->createPosition(['nombredelpuesto' => '']);

        $this->assertFalse($result['success']);
        $this->assertSame('Debe ingresar el nombre del puesto.', $result['message']);
    }

    public function test_create_returns_failure_when_name_is_whitespace(): void
    {
        $this->repo->expects($this->never())->method('create');

        $result = $this->service->createPosition(['nombredelpuesto' => '   ']);

        $this->assertFalse($result['success']);
    }

    public function test_create_returns_success_when_repository_succeeds(): void
    {
        $this->repo->method('create')->willReturn(true);

        $result = $this->service->createPosition(['nombredelpuesto' => 'Analista']);

        $this->assertTrue($result['success']);
        $this->assertSame('Registro agregado', $result['message']);
    }

    public function test_create_returns_failure_when_repository_returns_false(): void
    {
        $this->repo->method('create')->willReturn(false);

        $result = $this->service->createPosition(['nombredelpuesto' => 'Analista']);

        $this->assertFalse($result['success']);
        $this->assertSame('No se pudo agregar el registro.', $result['message']);
    }

    public function test_create_returns_failure_when_repository_throws_PDOException(): void
    {
        $this->repo->method('create')->willThrowException(new PDOException('DB error'));

        $result = $this->service->createPosition(['nombredelpuesto' => 'Analista']);

        $this->assertFalse($result['success']);
        $this->assertSame('No se pudo agregar el registro.', $result['message']);
    }

    public function test_create_passes_trimmed_name_to_repository(): void
    {
        $this->repo->expects($this->once())
            ->method('create')
            ->with('Director')
            ->willReturn(true);

        $this->service->createPosition(['nombredelpuesto' => '  Director  ']);
    }

    // --- updatePosition ---

    public function test_update_returns_failure_when_id_is_zero(): void
    {
        $this->repo->expects($this->never())->method('findById');

        $result = $this->service->updatePosition(0, ['nombredelpuesto' => 'X']);

        $this->assertFalse($result['success']);
        $this->assertSame('El ID del puesto no es válido.', $result['message']);
    }

    public function test_update_returns_failure_when_name_is_empty(): void
    {
        $result = $this->service->updatePosition(1, ['nombredelpuesto' => '']);

        $this->assertFalse($result['success']);
        $this->assertSame('Debe ingresar el nombre del puesto.', $result['message']);
    }

    public function test_update_returns_failure_when_position_not_found(): void
    {
        $this->repo->method('findById')->willReturn(null);

        $result = $this->service->updatePosition(99, ['nombredelpuesto' => 'Director']);

        $this->assertFalse($result['success']);
        $this->assertSame('No se encontró el puesto a editar.', $result['message']);
    }

    public function test_update_returns_success_when_repository_succeeds(): void
    {
        $this->repo->method('findById')->willReturn($this->makePosition());
        $this->repo->method('update')->willReturn(true);

        $result = $this->service->updatePosition(1, ['nombredelpuesto' => 'Director']);

        $this->assertTrue($result['success']);
        $this->assertSame('Registro actualizado', $result['message']);
    }

    public function test_update_returns_failure_when_repository_throws_PDOException(): void
    {
        $this->repo->method('findById')->willReturn($this->makePosition());
        $this->repo->method('update')->willThrowException(new PDOException('DB error'));

        $result = $this->service->updatePosition(1, ['nombredelpuesto' => 'Director']);

        $this->assertFalse($result['success']);
        $this->assertSame('No se pudo actualizar el registro.', $result['message']);
    }

    // --- deletePosition ---

    public function test_delete_returns_false_when_id_is_zero(): void
    {
        $this->repo->expects($this->never())->method('deleteById');

        $this->assertFalse($this->service->deletePosition(0));
    }

    public function test_delete_delegates_to_repository(): void
    {
        $this->repo->expects($this->once())->method('deleteById')->with(5)->willReturn(true);

        $this->assertTrue($this->service->deletePosition(5));
    }
}
