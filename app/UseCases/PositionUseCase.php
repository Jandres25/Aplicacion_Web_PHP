<?php

namespace App\UseCases;

use App\Repositories\PositionRepository;
use App\Services\PositionService;
use Config\Database;

class PositionUseCase
{
    private $positionService;

    public function __construct(PositionService $positionService)
    {
        $this->positionService = $positionService;
    }

    public static function fromEnvironment(): self
    {
        $repository = new PositionRepository(Database::getConnection());
        $service = new PositionService($repository);
        return new self($service);
    }

    public function listPositions()
    {
        return $this->positionService->listPositions();
    }

    public function getPosition($id)
    {
        return $this->positionService->getPosition($id);
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
