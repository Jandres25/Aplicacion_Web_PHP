<?php

namespace App\Services;

use App\Infrastructure\EmployeeFileStorage;
use App\Repositories\EmployeeRepository;
use PDOException;

class EmployeeService
{
    private $employeeRepository;
    private $fileStorage;

    public function __construct(EmployeeRepository $employeeRepository, EmployeeFileStorage $fileStorage)
    {
        $this->employeeRepository = $employeeRepository;
        $this->fileStorage = $fileStorage;
    }

    public function listEmployees()
    {
        return $this->employeeRepository->listAllWithPosition();
    }

    public function listPositions()
    {
        return $this->employeeRepository->listPositions();
    }

    public function getEmployee($id)
    {
        return $this->employeeRepository->findById((int)$id);
    }

    public function getEmployeeWithPosition($id)
    {
        return $this->employeeRepository->findByIdWithPosition((int)$id);
    }

    public function createEmployee($data, $files, $baseDirectory)
    {
        $validationError = $this->validateEmployeeData($data);
        if ($validationError !== null) {
            return ['success' => false, 'message' => $validationError];
        }

        $photoName = $this->fileStorage->storeUploadedFile(isset($files['foto']) ? $files['foto'] : [], $baseDirectory);
        $cvName = $this->fileStorage->storeUploadedFile(isset($files['CV']) ? $files['CV'] : [], $baseDirectory);

        try {
            $created = $this->employeeRepository->create([
                'Primernombre' => trim((string)($data['primernombre'] ?? '')),
                'Segundonombre' => trim((string)($data['segundonombre'] ?? '')),
                'Primerapellido' => trim((string)($data['primerapellido'] ?? '')),
                'Segundoapellido' => trim((string)($data['segundoapellido'] ?? '')),
                'Foto' => $photoName,
                'CV' => $cvName,
                'Idpuesto' => (int)($data['idpuesto'] ?? 0),
                'Fecha' => (string)($data['fechadeingreso'] ?? '')
            ]);
        } catch (PDOException $exception) {
            return ['success' => false, 'message' => 'No se pudo agregar el registro.'];
        }

        if (!$created) {
            return ['success' => false, 'message' => 'No se pudo agregar el registro.'];
        }

        return ['success' => true, 'message' => 'Registro agregado'];
    }

    public function updateEmployee($id, $data, $files, $baseDirectory)
    {
        $validationError = $this->validateEmployeeData($data);
        if ($validationError !== null) {
            return ['success' => false, 'message' => $validationError];
        }

        $employeeId = (int)$id;
        if ($employeeId <= 0) {
            return ['success' => false, 'message' => 'El ID del empleado no es válido.'];
        }

        $existingEmployee = $this->employeeRepository->findById($employeeId);
        if ($existingEmployee === null) {
            return ['success' => false, 'message' => 'No se encontró el empleado a editar.'];
        }

        $currentPhoto = isset($existingEmployee['Foto']) ? (string)$existingEmployee['Foto'] : '';
        $currentCv = isset($existingEmployee['CV']) ? (string)$existingEmployee['CV'] : '';

        $newPhoto = $this->fileStorage->storeUploadedFile(isset($files['foto']) ? $files['foto'] : [], $baseDirectory);
        if ($newPhoto !== '') {
            $this->fileStorage->deleteFileIfExists($baseDirectory, $currentPhoto);
            $currentPhoto = $newPhoto;
        }

        $newCv = $this->fileStorage->storeUploadedFile(isset($files['CV']) ? $files['CV'] : [], $baseDirectory);
        if ($newCv !== '') {
            $this->fileStorage->deleteFileIfExists($baseDirectory, $currentCv);
            $currentCv = $newCv;
        }

        try {
            $updated = $this->employeeRepository->update($employeeId, [
                'Primernombre' => trim((string)($data['primernombre'] ?? '')),
                'Segundonombre' => trim((string)($data['segundonombre'] ?? '')),
                'Primerapellido' => trim((string)($data['primerapellido'] ?? '')),
                'Segundoapellido' => trim((string)($data['segundoapellido'] ?? '')),
                'Foto' => $currentPhoto,
                'CV' => $currentCv,
                'Idpuesto' => (int)($data['idpuesto'] ?? 0),
                'Fecha' => (string)($data['fechadeingreso'] ?? '')
            ]);
        } catch (PDOException $exception) {
            return ['success' => false, 'message' => 'No se pudo actualizar el registro.'];
        }

        if (!$updated) {
            return ['success' => false, 'message' => 'No se pudo actualizar el registro.'];
        }

        return ['success' => true, 'message' => 'Registro actualizado'];
    }

    public function deleteEmployee($id, $baseDirectory)
    {
        $employeeId = (int)$id;
        if ($employeeId <= 0) {
            return false;
        }

        $files = $this->employeeRepository->findFilesById($employeeId);
        if ($files !== null) {
            $this->fileStorage->deleteFileIfExists($baseDirectory, isset($files['Foto']) ? $files['Foto'] : '');
            $this->fileStorage->deleteFileIfExists($baseDirectory, isset($files['CV']) ? $files['CV'] : '');
        }

        return $this->employeeRepository->deleteById($employeeId);
    }

    private function validateEmployeeData($data)
    {
        $primernombre = trim((string)($data['primernombre'] ?? ''));
        $primerapellido = trim((string)($data['primerapellido'] ?? ''));
        $segundoapellido = trim((string)($data['segundoapellido'] ?? ''));
        $idpuesto = (int)($data['idpuesto'] ?? 0);
        $fecha = (string)($data['fechadeingreso'] ?? '');

        if ($primernombre === '' || $primerapellido === '' || $segundoapellido === '') {
            return 'Debe completar los campos obligatorios del nombre y apellidos.';
        }

        if ($idpuesto <= 0) {
            return 'Debe seleccionar un puesto válido.';
        }

        if ($fecha === '' || !$this->isValidDate($fecha)) {
            return 'Debe seleccionar una fecha de ingreso válida.';
        }

        return null;
    }

    private function isValidDate($date)
    {
        $parsed = \DateTime::createFromFormat('Y-m-d', $date);
        return $parsed !== false && $parsed->format('Y-m-d') === $date;
    }
}
