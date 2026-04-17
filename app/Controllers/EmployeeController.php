<?php

namespace App\Controllers;

use App\Infrastructure\EmployeeFileStorage;
use App\Repositories\EmployeeRepository;
use App\Services\EmployeeService;
use Config\Database;

class EmployeeController
{
    private $employeeService;

    public function __construct(EmployeeService $employeeService)
    {
        $this->employeeService = $employeeService;
    }

    public static function fromEnvironment()
    {
        $repository = new EmployeeRepository(Database::getConnection());
        $fileStorage = new EmployeeFileStorage();
        $service = new EmployeeService($repository, $fileStorage);
        return new self($service);
    }

    public function listEmployees()
    {
        return $this->employeeService->listEmployees();
    }

    public function deleteEmployee($id, $baseDirectory)
    {
        return $this->employeeService->deleteEmployee($id, $baseDirectory);
    }

    public function listPositions()
    {
        return $this->employeeService->listPositions();
    }

    public function getEmployee($id)
    {
        return $this->employeeService->getEmployee($id);
    }

    public function getEmployeeWithPosition($id)
    {
        return $this->employeeService->getEmployeeWithPosition($id);
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
