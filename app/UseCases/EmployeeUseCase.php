<?php

namespace App\UseCases;

use App\Domain\Models\Employee;
use App\Domain\Models\Position;
use App\Http\Requests\Employees\StoreEmployeeRequest;
use App\Http\Requests\Employees\UpdateEmployeeRequest;
use App\Services\EmployeeService;
use App\UseCases\DTOs\OperationResult;

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

    public function listPositions(): array
    {
        return array_map(fn(Position $p) => $p->toArray(), $this->employeeService->listPositions());
    }

    public function getEmployee(int $id): ?array
    {
        $employee = $this->employeeService->getEmployee($id);
        return $employee?->toArray();
    }

    public function getEmployeeWithPosition(int $id): ?array
    {
        $employee = $this->employeeService->getEmployeeWithPosition($id);
        return $employee?->toArray();
    }

    public function createEmployee(StoreEmployeeRequest $req, array $files, string $baseDirectory): OperationResult
    {
        $result = $this->employeeService->createEmployee([
            'primernombre'    => $req->primerNombre,
            'segundonombre'   => $req->segundoNombre,
            'primerapellido'  => $req->primerApellido,
            'segundoapellido' => $req->segundoApellido,
            'idpuesto'        => $req->idPuesto,
            'fechadeingreso'  => $req->fechaIngreso,
        ], $files, $baseDirectory);

        return new OperationResult(
            (bool)($result['success'] ?? false),
            (string)($result['message'] ?? '')
        );
    }

    public function updateEmployee(UpdateEmployeeRequest $req, array $files, string $baseDirectory): OperationResult
    {
        $result = $this->employeeService->updateEmployee($req->id, [
            'primernombre'    => $req->primerNombre,
            'segundonombre'   => $req->segundoNombre,
            'primerapellido'  => $req->primerApellido,
            'segundoapellido' => $req->segundoApellido,
            'idpuesto'        => $req->idPuesto,
            'fechadeingreso'  => $req->fechaIngreso,
        ], $files, $baseDirectory);

        return new OperationResult(
            (bool)($result['success'] ?? false),
            (string)($result['message'] ?? '')
        );
    }

    public function deleteEmployee(int $id, string $baseDirectory): bool
    {
        return $this->employeeService->deleteEmployee($id, $baseDirectory);
    }
}
