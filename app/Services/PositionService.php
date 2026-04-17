<?php

namespace App\Services;

use App\Repositories\PositionRepository;
use PDOException;

class PositionService
{
    private $positionRepository;

    public function __construct(PositionRepository $positionRepository)
    {
        $this->positionRepository = $positionRepository;
    }

    public function listPositions()
    {
        return $this->positionRepository->listAll();
    }

    public function getPosition($id)
    {
        $positionId = (int)$id;
        if ($positionId <= 0) {
            return null;
        }
        return $this->positionRepository->findById($positionId);
    }

    public function createPosition($data)
    {
        $name = $this->sanitizeName($data);
        if ($name === '') {
            return ['success' => false, 'message' => 'Debe ingresar el nombre del puesto.'];
        }

        try {
            $created = $this->positionRepository->create($name);
        } catch (PDOException $exception) {
            return ['success' => false, 'message' => 'No se pudo agregar el registro.'];
        }

        if (!$created) {
            return ['success' => false, 'message' => 'No se pudo agregar el registro.'];
        }

        return ['success' => true, 'message' => 'Registro agregado'];
    }

    public function updatePosition($id, $data)
    {
        $positionId = (int)$id;
        if ($positionId <= 0) {
            return ['success' => false, 'message' => 'El ID del puesto no es válido.'];
        }

        $name = $this->sanitizeName($data);
        if ($name === '') {
            return ['success' => false, 'message' => 'Debe ingresar el nombre del puesto.'];
        }

        $existingPosition = $this->positionRepository->findById($positionId);
        if ($existingPosition === null) {
            return ['success' => false, 'message' => 'No se encontró el puesto a editar.'];
        }

        try {
            $updated = $this->positionRepository->update($positionId, $name);
        } catch (PDOException $exception) {
            return ['success' => false, 'message' => 'No se pudo actualizar el registro.'];
        }

        if (!$updated) {
            return ['success' => false, 'message' => 'No se pudo actualizar el registro.'];
        }

        return ['success' => true, 'message' => 'Registro actualizado'];
    }

    public function deletePosition($id)
    {
        $positionId = (int)$id;
        if ($positionId <= 0) {
            return false;
        }

        return $this->positionRepository->deleteById($positionId);
    }

    private function sanitizeName($data)
    {
        return trim((string)($data['nombredelpuesto'] ?? ''));
    }
}
