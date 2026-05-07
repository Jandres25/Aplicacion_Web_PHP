<?php

namespace App\UseCases;

use App\Domain\Models\Employee;
use App\Domain\Models\Position;
use App\Services\EmployeeService;

class EmployeeUseCase
{
    private EmployeeService $employeeService;

    public function __construct(EmployeeService $employeeService)
    {
        $this->employeeService = $employeeService;
    }

    public function listEmployees(): array
    {
        return array_map(fn(Employee $e) => $e->toArray(), $this->employeeService->listEmployees());
    }

    public function deleteEmployee($id, $baseDirectory)
    {
        return $this->employeeService->deleteEmployee($id, $baseDirectory);
    }

    public function listPositions(): array
    {
        return array_map(fn(Position $p) => $p->toArray(), $this->employeeService->listPositions());
    }

    public function getEmployee($id): ?array
    {
        $employee = $this->employeeService->getEmployee($id);
        return $employee?->toArray();
    }

    public function getEmployeeWithPosition($id): ?array
    {
        $employee = $this->employeeService->getEmployeeWithPosition($id);
        return $employee?->toArray();
    }

    public function createEmployee($data, $files, $baseDirectory)
    {
        return $this->employeeService->createEmployee($data, $files, $baseDirectory);
    }

    public function updateEmployee($id, $data, $files, $baseDirectory)
    {
        return $this->employeeService->updateEmployee($id, $data, $files, $baseDirectory);
    }
}
