<?php

namespace App\UseCases;

use App\Domain\Models\Position;
use App\Services\PositionService;

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

    public function getPosition($id): ?array
    {
        $position = $this->positionService->getPosition($id);
        return $position?->toArray();
    }

    public function createPosition($data)
    {
        return $this->positionService->createPosition($data);
    }

    public function updatePosition($id, $data)
    {
        return $this->positionService->updatePosition($id, $data);
    }

    public function deletePosition($id)
    {
        return $this->positionService->deletePosition($id);
    }
}
