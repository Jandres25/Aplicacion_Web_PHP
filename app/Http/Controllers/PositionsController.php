<?php

namespace App\Http\Controllers;

use App\UseCases\PositionUseCase;
use Core\Flash;

class PositionsController extends Controller
{
    public function index(): void
    {
        $this->requireLogin();
        $positionUseCase = PositionUseCase::fromEnvironment();
        $lista_tbl_puestos = $positionUseCase->listPositions();
        $this->renderWithLayout(
            'positions/index.php',
            array_merge(
                compact('lista_tbl_puestos'),
                $this->pageHeaderData(
                    'Gestión de Puestos',
                    'fas fa-briefcase',
                    $this->moduleBreadcrumbs('Puestos', 'puestos', 'fas fa-briefcase')
                )
            )
        );
    }

    public function createForm(): void
    {
        $this->requireLogin();
        $formAction = 'puestos-crear';
        $mensaje = '';
        $this->renderWithLayout(
            'positions/create.php',
            array_merge(
                compact('formAction', 'mensaje'),
                $this->pageHeaderData(
                    'Nuevo Puesto',
                    'fas fa-plus-circle',
                    $this->moduleBreadcrumbs('Puestos', 'puestos', 'fas fa-briefcase', 'Nuevo', 'fas fa-plus')
                )
            )
        );
    }

    public function create(): void
    {
        $this->requireLogin();
        if (!$this->hasValidCsrfToken($_POST)) {
            Flash::set('Solicitud inválida, recargue la página e intente nuevamente.', 'error');
            $this->redirect('puestos-crear');
        }

        $positionUseCase = PositionUseCase::fromEnvironment();
        $result = $positionUseCase->createPosition($_POST);
        if (($result['success'] ?? false) === true) {
            Flash::set((string)($result['message'] ?? 'Registro agregado'));
            $this->redirect('puestos');
        }

        $formAction = 'puestos-crear';
        $mensaje = (string)($result['message'] ?? 'No se pudo agregar el registro.');
        $this->renderWithLayout(
            'positions/create.php',
            array_merge(
                compact('formAction', 'mensaje'),
                $this->pageHeaderData(
                    'Nuevo Puesto',
                    'fas fa-plus-circle',
                    $this->moduleBreadcrumbs('Puestos', 'puestos', 'fas fa-briefcase', 'Nuevo', 'fas fa-plus')
                )
            )
        );
    }

    public function editForm(): void
    {
        $this->requireLogin();
        $positionUseCase = PositionUseCase::fromEnvironment();
        $txtID = (int)($_GET['txtID'] ?? 0);
        $puesto = $positionUseCase->getPosition($txtID);
        if ($puesto === null) {
            Flash::set('No se encontró el puesto a editar.', 'error');
            $this->redirect('puestos');
        }

        $formAction       = 'puestos-editar';
        $mensaje          = '';
        $nombredelpuesto  = (string)($puesto['Nombredelpuesto'] ?? '');
        $this->renderWithLayout(
            'positions/edit.php',
            array_merge(
                compact('formAction', 'mensaje', 'txtID', 'nombredelpuesto'),
                $this->pageHeaderData(
                    'Editar Puesto',
                    'fas fa-edit',
                    $this->moduleBreadcrumbs('Puestos', 'puestos', 'fas fa-briefcase', 'Editar', 'fas fa-pen')
                )
            )
        );
    }

    public function edit(): void
    {
        $this->requireLogin();
        if (!$this->hasValidCsrfToken($_POST)) {
            Flash::set('Solicitud inválida, recargue la página e intente nuevamente.', 'error');
            $this->redirect('puestos');
        }

        $positionUseCase = PositionUseCase::fromEnvironment();
        $txtID = (int)($_POST['txtID'] ?? 0);
        $result = $positionUseCase->updatePosition($txtID, $_POST);
        if (($result['success'] ?? false) === true) {
            Flash::set((string)($result['message'] ?? 'Registro actualizado'));
            $this->redirect('puestos');
        }

        $formAction      = 'puestos-editar';
        $mensaje         = (string)($result['message'] ?? 'No se pudo actualizar el registro.');
        $nombredelpuesto = trim((string)($_POST['nombredelpuesto'] ?? ''));
        $this->renderWithLayout(
            'positions/edit.php',
            array_merge(
                compact('formAction', 'mensaje', 'txtID', 'nombredelpuesto'),
                $this->pageHeaderData(
                    'Editar Puesto',
                    'fas fa-edit',
                    $this->moduleBreadcrumbs('Puestos', 'puestos', 'fas fa-briefcase', 'Editar', 'fas fa-pen')
                )
            )
        );
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
            $this->redirect('puestos');
        }

        $positionUseCase = PositionUseCase::fromEnvironment();
        $txtID = (int)($_POST['txtID'] ?? 0);

        if ($txtID > 0) {
            $deleted = $positionUseCase->deletePosition($txtID);
            $success = $deleted;
            $message = $deleted ? 'Registro borrado' : 'No se pudo borrar el registro';
        } else {
            $success = false;
            $message = 'El ID del puesto no es válido.';
        }

        if ($isAjax) {
            header('Content-Type: application/json');
            echo json_encode(['success' => $success, 'message' => $message]);
            exit;
        }

        Flash::set($message, $success ? 'success' : 'error');
        $this->redirect('puestos');
    }
}
