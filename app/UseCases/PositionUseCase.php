<?php

namespace App\UseCases;

use App\Domain\Models\Position;
use App\Http\Requests\Positions\StorePositionRequest;
use App\Http\Requests\Positions\UpdatePositionRequest;
use App\Services\PositionService;
use App\UseCases\DTOs\OperationResult;

class PositionUseCase
{
    private PositionService $positionService;

    public function __construct(PositionService $positionService)
    {
        $this->positionService = $positionService;
    }

    public function listPositions(): array
    {
        return array_map(fn(Position $p) => $p->toArray(), $this->positionService->listPositions());
    }

    public function getPosition(int $id): ?array
    {
        $position = $this->positionService->getPosition($id);
        return $position?->toArray();
    }

    public function createPosition(StorePositionRequest $req): OperationResult
    {
        $result = $this->positionService->createPosition(['nombredelpuesto' => $req->nombre]);
        return new OperationResult(
            (bool)($result['success'] ?? false),
            (string)($result['message'] ?? '')
        );
    }

    public function updatePosition(UpdatePositionRequest $req): OperationResult
    {
        $result = $this->positionService->updatePosition($req->id, ['nombredelpuesto' => $req->nombre]);
        return new OperationResult(
            (bool)($result['success'] ?? false),
            (string)($result['message'] ?? '')
        );
    }

    public function deletePosition(int $id): bool
    {
        return $this->positionService->deletePosition($id);
    }
}
