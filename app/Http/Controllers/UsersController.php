<?php

namespace App\Http\Controllers;

use App\UseCases\UserUseCase;
use Core\Flash;

class UsersController extends Controller
{
    public function index(): void
    {
        $this->requireLogin();
        $this->requireAdmin();
        $userUseCase = UserUseCase::fromEnvironment();
        $lista_tbl_usuarios = $userUseCase->listUsers();
        $this->renderWithLayout(
            'users/index.php',
            array_merge(
                compact('lista_tbl_usuarios'),
                $this->pageHeaderData(
                    'Gestión de Usuarios',
                    'fas fa-users-cog',
                    $this->moduleBreadcrumbs('Usuarios', 'usuarios', 'fas fa-users-cog')
                )
            )
        );
    }

    public function createForm(): void
    {
        $this->requireLogin();
        $this->requireAdmin();
        $formAction = 'usuarios-crear';
        $mensaje = '';
        $this->renderWithLayout(
            'users/create.php',
            array_merge(
                compact('formAction', 'mensaje'),
                $this->pageHeaderData(
                    'Nuevo Usuario',
                    'fas fa-user-plus',
                    $this->moduleBreadcrumbs('Usuarios', 'usuarios', 'fas fa-users-cog', 'Nuevo', 'fas fa-plus')
                )
            )
        );
    }

    public function create(): void
    {
        $this->requireLogin();
        $this->requireAdmin();
        if (!$this->hasValidCsrfToken($_POST)) {
            Flash::set('Solicitud inválida, recargue la página e intente nuevamente.', 'error');
            $this->redirect('usuarios-crear');
        }

        $userUseCase = UserUseCase::fromEnvironment();
        $result = $userUseCase->createUser($_POST);
        if (($result['success'] ?? false) === true) {
            Flash::set((string)($result['message'] ?? 'Registro agregado'));
            $this->redirect('usuarios');
        }

        $formAction = 'usuarios-crear';
        $mensaje = (string)($result['message'] ?? 'No se pudo agregar el registro.');
        $this->renderWithLayout(
            'users/create.php',
            array_merge(
                compact('formAction', 'mensaje'),
                $this->pageHeaderData(
                    'Nuevo Usuario',
                    'fas fa-user-plus',
                    $this->moduleBreadcrumbs('Usuarios', 'usuarios', 'fas fa-users-cog', 'Nuevo', 'fas fa-plus')
                )
            )
        );
    }

    public function editForm(): void
    {
        $this->requireLogin();
        $this->requireAdmin();
        $userUseCase = UserUseCase::fromEnvironment();
        $txtID = (int)($_GET['txtID'] ?? 0);
        $usuarioData = $userUseCase->getUser($txtID);
        if ($usuarioData === null) {
            Flash::set('No se encontró el usuario a editar.', 'error');
            $this->redirect('usuarios');
        }

        $formAction = 'usuarios-editar';
        $mensaje    = '';
        $usuario    = (string)($usuarioData['Nombreusuario'] ?? '');
        $correo     = (string)($usuarioData['Correo'] ?? '');
        $this->renderWithLayout(
            'users/edit.php',
            array_merge(
                compact('formAction', 'mensaje', 'txtID', 'usuario', 'correo'),
                $this->pageHeaderData(
                    'Editar Usuario',
                    'fas fa-user-edit',
                    $this->moduleBreadcrumbs('Usuarios', 'usuarios', 'fas fa-users-cog', 'Editar', 'fas fa-pen')
                )
            )
        );
    }

    public function edit(): void
    {
        $this->requireLogin();
        $this->requireAdmin();
        if (!$this->hasValidCsrfToken($_POST)) {
            Flash::set('Solicitud inválida, recargue la página e intente nuevamente.', 'error');
            $this->redirect('usuarios');
        }

        $userUseCase = UserUseCase::fromEnvironment();
        $txtID = (int)($_POST['txtID'] ?? 0);
        $result = $userUseCase->updateUser($txtID, $_POST);
        if (($result['success'] ?? false) === true) {
            Flash::set((string)($result['message'] ?? 'Registro actualizado'));
            $this->redirect('usuarios');
        }

        $formAction = 'usuarios-editar';
        $mensaje    = (string)($result['message'] ?? 'No se pudo actualizar el registro.');
        $usuario    = trim((string)($_POST['usuario'] ?? ''));
        $correo     = trim((string)($_POST['correo'] ?? ''));
        $this->renderWithLayout(
            'users/edit.php',
            array_merge(
                compact('formAction', 'mensaje', 'txtID', 'usuario', 'correo'),
                $this->pageHeaderData(
                    'Editar Usuario',
                    'fas fa-user-edit',
                    $this->moduleBreadcrumbs('Usuarios', 'usuarios', 'fas fa-users-cog', 'Editar', 'fas fa-pen')
                )
            )
        );
    }

    public function delete(): void
    {
        $this->requireLogin();
        $this->requireAdmin();
        $isAjax = $this->isAjaxRequest();

        if (!$this->hasValidCsrfToken($_POST)) {
            $msg = 'Solicitud inválida, recargue la página e intente nuevamente.';
            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => $msg]);
                exit;
            }
            Flash::set($msg, 'error');
            $this->redirect('usuarios');
        }

        $userUseCase = UserUseCase::fromEnvironment();
        $txtID = (int)($_POST['txtID'] ?? 0);

        if ($txtID > 0) {
            $deleted = $userUseCase->deleteUser($txtID);
            $success = $deleted;
            $message = $deleted ? 'Registro borrado' : 'No se pudo borrar el registro';
        } else {
            $success = false;
            $message = 'El ID del usuario no es válido.';
        }

        if ($isAjax) {
            header('Content-Type: application/json');
            echo json_encode(['success' => $success, 'message' => $message]);
            exit;
        }

        Flash::set($message, $success ? 'success' : 'error');
        $this->redirect('usuarios');
    }
}
