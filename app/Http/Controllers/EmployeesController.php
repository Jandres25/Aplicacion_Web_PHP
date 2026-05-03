<?php

namespace App\Http\Controllers;

use App\UseCases\EmployeeUseCase;
use Core\Flash;
use Core\View;

class EmployeesController extends Controller
{
    public function index(): void
    {
        $this->requireLogin();
        $employeeUseCase = EmployeeUseCase::fromEnvironment();
        $lista_tbl_empleados = $employeeUseCase->listEmployees();
        $this->renderWithLayout(
            'employees/index.php',
            array_merge(
                compact('lista_tbl_empleados'),
                $this->pageHeaderData(
                    'Gestión de Empleados',
                    'fas fa-users',
                    $this->moduleBreadcrumbs('Empleados', 'empleados', 'fas fa-users')
                )
            )
        );
    }

    public function createForm(): void
    {
        $this->requireLogin();
        $employeeUseCase = EmployeeUseCase::fromEnvironment();
        $lista_tbl_puestos = $employeeUseCase->listPositions();
        $formAction = 'empleados-crear';
        $mensaje = '';
        $this->renderWithLayout(
            'employees/create.php',
            array_merge(
                compact('lista_tbl_puestos', 'formAction', 'mensaje'),
                $this->pageHeaderData(
                    'Nuevo Empleado',
                    'fas fa-user-plus',
                    $this->moduleBreadcrumbs('Empleados', 'empleados', 'fas fa-users', 'Nuevo', 'fas fa-plus')
                )
            )
        );
    }

    public function create(): void
    {
        $this->requireLogin();
        if (!$this->hasValidCsrfToken($_POST)) {
            Flash::set('Solicitud inválida, recargue la página e intente nuevamente.', 'error');
            $this->redirect('empleados-crear');
        }

        $employeeUseCase = EmployeeUseCase::fromEnvironment();
        $result = $employeeUseCase->createEmployee($_POST, $_FILES, $this->uploadsDirectory);
        if (($result['success'] ?? false) === true) {
            Flash::set((string)($result['message'] ?? 'Registro agregado'));
            $this->redirect('empleados');
        }

        $lista_tbl_puestos = $employeeUseCase->listPositions();
        $formAction = 'empleados-crear';
        $mensaje = (string)($result['message'] ?? 'No se pudo agregar el registro.');
        $this->renderWithLayout(
            'employees/create.php',
            array_merge(
                compact('lista_tbl_puestos', 'formAction', 'mensaje'),
                $this->pageHeaderData(
                    'Nuevo Empleado',
                    'fas fa-user-plus',
                    $this->moduleBreadcrumbs('Empleados', 'empleados', 'fas fa-users', 'Nuevo', 'fas fa-plus')
                )
            )
        );
    }

    public function editForm(): void
    {
        $this->requireLogin();
        $employeeUseCase = EmployeeUseCase::fromEnvironment();
        $txtID = (int)($_GET['txtID'] ?? 0);
        $empleado = $employeeUseCase->getEmployee($txtID);
        if ($empleado === null) {
            Flash::set('No se encontró el empleado a editar.', 'error');
            $this->redirect('empleados');
        }

        $lista_tbl_puestos = $employeeUseCase->listPositions();
        $formAction        = 'empleados-editar';
        $mensaje           = '';
        $primernombre      = (string)($empleado['Primernombre'] ?? '');
        $segundonombre     = (string)($empleado['Segundonombre'] ?? '');
        $primerapellido    = (string)($empleado['Primerapellido'] ?? '');
        $segundoapellido   = (string)($empleado['Segundoapellido'] ?? '');
        $foto              = (string)($empleado['Foto'] ?? '');
        $cv                = (string)($empleado['CV'] ?? '');
        $idpuesto          = (string)($empleado['Idpuesto'] ?? '');
        $fechadeingreso    = (string)($empleado['Fecha'] ?? '');

        $this->renderWithLayout(
            'employees/edit.php',
            array_merge(
                compact(
                    'lista_tbl_puestos', 'formAction', 'mensaje', 'txtID',
                    'primernombre', 'segundonombre', 'primerapellido', 'segundoapellido',
                    'foto', 'cv', 'idpuesto', 'fechadeingreso'
                ),
                $this->pageHeaderData(
                    'Editar Empleado',
                    'fas fa-user-edit',
                    $this->moduleBreadcrumbs('Empleados', 'empleados', 'fas fa-users', 'Editar', 'fas fa-pen')
                )
            )
        );
    }

    public function edit(): void
    {
        $this->requireLogin();
        if (!$this->hasValidCsrfToken($_POST)) {
            Flash::set('Solicitud inválida, recargue la página e intente nuevamente.', 'error');
            $this->redirect('empleados');
        }

        $employeeUseCase = EmployeeUseCase::fromEnvironment();
        $txtID = (int)($_POST['txtID'] ?? 0);
        $result = $employeeUseCase->updateEmployee($txtID, $_POST, $_FILES, $this->uploadsDirectory);
        if (($result['success'] ?? false) === true) {
            Flash::set((string)($result['message'] ?? 'Registro actualizado'));
            $this->redirect('empleados');
        }

        $empleado = $employeeUseCase->getEmployee($txtID);
        if ($empleado === null) {
            Flash::set('No se encontró el empleado a editar.', 'error');
            $this->redirect('empleados');
        }

        $lista_tbl_puestos = $employeeUseCase->listPositions();
        $formAction        = 'empleados-editar';
        $mensaje           = (string)($result['message'] ?? 'No se pudo actualizar el registro.');
        $primernombre      = trim((string)($_POST['primernombre'] ?? $empleado['Primernombre'] ?? ''));
        $segundonombre     = trim((string)($_POST['segundonombre'] ?? $empleado['Segundonombre'] ?? ''));
        $primerapellido    = trim((string)($_POST['primerapellido'] ?? $empleado['Primerapellido'] ?? ''));
        $segundoapellido   = trim((string)($_POST['segundoapellido'] ?? $empleado['Segundoapellido'] ?? ''));
        $foto              = (string)($empleado['Foto'] ?? '');
        $cv                = (string)($empleado['CV'] ?? '');
        $idpuesto          = (string)($_POST['idpuesto'] ?? $empleado['Idpuesto'] ?? '');
        $fechadeingreso    = (string)($_POST['fechadeingreso'] ?? $empleado['Fecha'] ?? '');

        $this->renderWithLayout(
            'employees/edit.php',
            array_merge(
                compact(
                    'lista_tbl_puestos', 'formAction', 'mensaje', 'txtID',
                    'primernombre', 'segundonombre', 'primerapellido', 'segundoapellido',
                    'foto', 'cv', 'idpuesto', 'fechadeingreso'
                ),
                $this->pageHeaderData(
                    'Editar Empleado',
                    'fas fa-user-edit',
                    $this->moduleBreadcrumbs('Empleados', 'empleados', 'fas fa-users', 'Editar', 'fas fa-pen')
                )
            )
        );
    }

    public function recommendation(): void
    {
        $this->requireLogin();
        $employeeUseCase = EmployeeUseCase::fromEnvironment();
        $txtID = (int)($_GET['txtID'] ?? 0);
        $empleado = $employeeUseCase->getEmployeeWithPosition($txtID);
        if ($empleado === null) {
            Flash::set('No se encontró el empleado para la carta de recomendación.', 'error');
            $this->redirect('empleados');
        }

        $nombreCompleto = trim(preg_replace('/\s+/', ' ', implode(' ', [
            (string)($empleado['Primernombre'] ?? ''),
            (string)($empleado['Segundonombre'] ?? ''),
            (string)($empleado['Primerapellido'] ?? ''),
            (string)($empleado['Segundoapellido'] ?? ''),
        ])));
        $puesto      = (string)($empleado['puesto'] ?? '');
        $fechaIngreso = \DateTime::createFromFormat('Y-m-d', (string)($empleado['Fecha'] ?? ''));
        $fechaIngreso = $fechaIngreso ?: new \DateTime();
        $diferencia  = $fechaIngreso->diff(new \DateTime());
        $fechaActual = (new \DateTime())->format('d/m/Y');

        View::render('employees/recommendation_letter.php', compact(
            'nombreCompleto', 'puesto', 'fechaIngreso', 'diferencia', 'fechaActual'
        ));
    }

    public function delete(): void
    {
        $this->requireLogin();
        $isAjax = $this->isAjaxRequest();

        if (!$this->hasValidCsrfToken($_POST)) {
            $msg = 'Solicitud inválida, recargue la página e intente nuevamente.';
            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => $msg]);
                exit;
            }
            Flash::set($msg, 'error');
            $this->redirect('empleados');
        }

        $employeeUseCase = EmployeeUseCase::fromEnvironment();
        $txtID = (int)($_POST['txtID'] ?? 0);

        if ($txtID > 0) {
            $deleted = $employeeUseCase->deleteEmployee($txtID, $this->uploadsDirectory);
            $success = $deleted;
            $message = $deleted ? 'Registro borrado' : 'No se pudo borrar el registro';
        } else {
            $success = false;
            $message = 'El ID del empleado no es válido.';
        }

        if ($isAjax) {
            header('Content-Type: application/json');
            echo json_encode(['success' => $success, 'message' => $message]);
            exit;
        }

        Flash::set($message, $success ? 'success' : 'error');
        $this->redirect('empleados');
    }
}
