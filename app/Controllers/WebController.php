<?php

namespace App\Controllers;

require_once __DIR__ . '/../../core/Flash.php';
require_once __DIR__ . '/../../core/Security.php';
require_once __DIR__ . '/../Middleware/AuthMiddleware.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../Repositories/UserRepository.php';
require_once __DIR__ . '/../Repositories/EmployeeRepository.php';
require_once __DIR__ . '/../Repositories/PositionRepository.php';
require_once __DIR__ . '/../Services/AuthService.php';
require_once __DIR__ . '/../Services/EmployeeService.php';
require_once __DIR__ . '/../Services/UserService.php';
require_once __DIR__ . '/../Services/PositionService.php';
require_once __DIR__ . '/../Infrastructure/EmployeeFileStorage.php';
require_once __DIR__ . '/AuthController.php';
require_once __DIR__ . '/EmployeeController.php';
require_once __DIR__ . '/PositionController.php';
require_once __DIR__ . '/UserController.php';

use App\Middleware\AuthMiddleware;
use Core\Flash;
use Core\Security;

class WebController
{
    private $projectRoot;
    private $publicBaseUrl;
    private $uploadsDirectory;

    public function __construct($projectRoot, $publicBaseUrl)
    {
        $this->projectRoot = rtrim((string)$projectRoot, '/');
        $this->publicBaseUrl = rtrim((string)$publicBaseUrl, '/') . '/';
        $this->uploadsDirectory = $this->projectRoot . '/public/storage/uploads';
    }

    public function home()
    {
        $this->requireLogin();
        $nombreUsuario = $this->currentUser();
        $esAdministrador = $nombreUsuario === 'Administrador';
        $this->renderWithLayout('home/index.php', compact('nombreUsuario', 'esAdministrador'));
    }

    public function homeAlias()
    {
        $this->redirect('');
    }

    public function showLogin()
    {
        Security::startSession();

        if (isset($_SESSION['logueado'])) {
            $this->redirect('');
        }

        $public_base = $this->publicBaseUrl;
        $formAction = 'login';
        $mensaje = '';
        $csrfToken = Security::getCsrfToken();
        require $this->projectRoot . '/app/Views/auth/login.php';
    }

    public function login()
    {
        if (!$this->hasValidCsrfToken($_POST)) {
            $public_base = $this->publicBaseUrl;
            $formAction = 'login';
            $mensaje = 'Solicitud inválida, recargue la página e intente nuevamente.';
            $csrfToken = Security::getCsrfToken();
            require $this->projectRoot . '/app/Views/auth/login.php';
            return;
        }

        $authController = AuthController::fromEnvironment();
        $result = $authController->handleLogin($_POST);

        $public_base = $this->publicBaseUrl;
        $formAction = 'login';
        $mensaje = isset($result['mensaje']) ? (string)$result['mensaje'] : '';
        $csrfToken = Security::getCsrfToken();
        require $this->projectRoot . '/app/Views/auth/login.php';
    }

    public function logout()
    {
        if (!$this->hasValidCsrfToken($_POST)) {
            Flash::set('Solicitud inválida, recargue la página e intente nuevamente.', 'error');
            $this->redirect('');
        }

        Security::startSession();

        session_unset();
        session_destroy();
        $this->redirect('login');
    }

    public function employeesIndex()
    {
        $this->requireLogin();
        $employeeController = EmployeeController::fromEnvironment();

        $lista_tbl_empleados = $employeeController->listEmployees();
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

    public function employeesCreateForm()
    {
        $this->requireLogin();
        $employeeController = EmployeeController::fromEnvironment();
        $lista_tbl_puestos = $employeeController->listPositions();
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

    public function employeesCreate()
    {
        $this->requireLogin();
        if (!$this->hasValidCsrfToken($_POST)) {
            Flash::set('Solicitud inválida, recargue la página e intente nuevamente.', 'error');
            $this->redirect('empleados-crear');
        }

        $employeeController = EmployeeController::fromEnvironment();
        $result = $employeeController->createEmployee($_POST, $_FILES, $this->uploadsDirectory);
        if (($result['success'] ?? false) === true) {
            Flash::set((string)($result['message'] ?? 'Registro agregado'));
            $this->redirect('empleados');
        }

        $lista_tbl_puestos = $employeeController->listPositions();
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

    public function employeesEditForm()
    {
        $this->requireLogin();
        $employeeController = EmployeeController::fromEnvironment();
        $txtID = (int)($_GET['txtID'] ?? 0);
        $empleado = $employeeController->getEmployee($txtID);
        if ($empleado === null) {
            Flash::set('No se encontró el empleado a editar.', 'error');
            $this->redirect('empleados');
        }

        $lista_tbl_puestos = $employeeController->listPositions();
        $formAction = 'empleados-editar';
        $mensaje = '';
        $primernombre = (string)($empleado['Primernombre'] ?? '');
        $segundonombre = (string)($empleado['Segundonombre'] ?? '');
        $primerapellido = (string)($empleado['Primerapellido'] ?? '');
        $segundoapellido = (string)($empleado['Segundoapellido'] ?? '');
        $foto = (string)($empleado['Foto'] ?? '');
        $cv = (string)($empleado['CV'] ?? '');
        $idpuesto = (string)($empleado['Idpuesto'] ?? '');
        $fechadeingreso = (string)($empleado['Fecha'] ?? '');

        $this->renderWithLayout(
            'employees/edit.php',
            array_merge(
                compact(
                    'lista_tbl_puestos',
                    'formAction',
                    'mensaje',
                    'txtID',
                    'primernombre',
                    'segundonombre',
                    'primerapellido',
                    'segundoapellido',
                    'foto',
                    'cv',
                    'idpuesto',
                    'fechadeingreso'
                ),
                $this->pageHeaderData(
                    'Editar Empleado',
                    'fas fa-user-edit',
                    $this->moduleBreadcrumbs('Empleados', 'empleados', 'fas fa-users', 'Editar', 'fas fa-pen')
                )
            )
        );
    }

    public function employeesEdit()
    {
        $this->requireLogin();
        if (!$this->hasValidCsrfToken($_POST)) {
            Flash::set('Solicitud inválida, recargue la página e intente nuevamente.', 'error');
            $this->redirect('empleados');
        }

        $employeeController = EmployeeController::fromEnvironment();
        $txtID = (int)($_POST['txtID'] ?? 0);
        $result = $employeeController->updateEmployee($txtID, $_POST, $_FILES, $this->uploadsDirectory);
        if (($result['success'] ?? false) === true) {
            Flash::set((string)($result['message'] ?? 'Registro actualizado'));
            $this->redirect('empleados');
        }

        $empleado = $employeeController->getEmployee($txtID);
        if ($empleado === null) {
            Flash::set('No se encontró el empleado a editar.', 'error');
            $this->redirect('empleados');
        }

        $lista_tbl_puestos = $employeeController->listPositions();
        $formAction = 'empleados-editar';
        $mensaje = (string)($result['message'] ?? 'No se pudo actualizar el registro.');
        $primernombre = trim((string)($_POST['primernombre'] ?? $empleado['Primernombre'] ?? ''));
        $segundonombre = trim((string)($_POST['segundonombre'] ?? $empleado['Segundonombre'] ?? ''));
        $primerapellido = trim((string)($_POST['primerapellido'] ?? $empleado['Primerapellido'] ?? ''));
        $segundoapellido = trim((string)($_POST['segundoapellido'] ?? $empleado['Segundoapellido'] ?? ''));
        $foto = (string)($empleado['Foto'] ?? '');
        $cv = (string)($empleado['CV'] ?? '');
        $idpuesto = (string)($_POST['idpuesto'] ?? $empleado['Idpuesto'] ?? '');
        $fechadeingreso = (string)($_POST['fechadeingreso'] ?? $empleado['Fecha'] ?? '');

        $this->renderWithLayout(
            'employees/edit.php',
            array_merge(
                compact(
                    'lista_tbl_puestos',
                    'formAction',
                    'mensaje',
                    'txtID',
                    'primernombre',
                    'segundonombre',
                    'primerapellido',
                    'segundoapellido',
                    'foto',
                    'cv',
                    'idpuesto',
                    'fechadeingreso'
                ),
                $this->pageHeaderData(
                    'Editar Empleado',
                    'fas fa-user-edit',
                    $this->moduleBreadcrumbs('Empleados', 'empleados', 'fas fa-users', 'Editar', 'fas fa-pen')
                )
            )
        );
    }

    public function employeeRecommendation()
    {
        $this->requireLogin();
        $employeeController = EmployeeController::fromEnvironment();
        $txtID = (int)($_GET['txtID'] ?? 0);
        $empleado = $employeeController->getEmployeeWithPosition($txtID);
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
        $puesto = (string)($empleado['puesto'] ?? '');
        $fechaIngreso = \DateTime::createFromFormat('Y-m-d', (string)($empleado['Fecha'] ?? ''));
        $fechaIngreso = $fechaIngreso ?: new \DateTime();
        $diferencia = $fechaIngreso->diff(new \DateTime());
        $fechaActual = (new \DateTime())->format('d/m/Y');
        require $this->projectRoot . '/app/Views/employees/recommendation_letter.php';
    }

    public function employeesDelete()
    {
        $this->requireLogin();
        if (!$this->hasValidCsrfToken($_POST)) {
            Flash::set('Solicitud inválida, recargue la página e intente nuevamente.', 'error');
            $this->redirect('empleados');
        }

        $employeeController = EmployeeController::fromEnvironment();
        $txtID = (int)($_POST['txtID'] ?? 0);
        if ($txtID > 0) {
            $deleted = $employeeController->deleteEmployee($txtID, $this->uploadsDirectory);
            Flash::set($deleted ? 'Registro borrado' : 'No se pudo borrar el registro', $deleted ? 'success' : 'error');
        } else {
            Flash::set('El ID del empleado no es válido.', 'error');
        }

        $this->redirect('empleados');
    }

    public function positionsIndex()
    {
        $this->requireLogin();
        $positionController = PositionController::fromEnvironment();

        $lista_tbl_puestos = $positionController->listPositions();
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

    public function positionsCreateForm()
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

    public function positionsCreate()
    {
        $this->requireLogin();
        if (!$this->hasValidCsrfToken($_POST)) {
            Flash::set('Solicitud inválida, recargue la página e intente nuevamente.', 'error');
            $this->redirect('puestos-crear');
        }

        $positionController = PositionController::fromEnvironment();
        $result = $positionController->createPosition($_POST);
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

    public function positionsEditForm()
    {
        $this->requireLogin();
        $positionController = PositionController::fromEnvironment();
        $txtID = (int)($_GET['txtID'] ?? 0);
        $puesto = $positionController->getPosition($txtID);
        if ($puesto === null) {
            Flash::set('No se encontró el puesto a editar.', 'error');
            $this->redirect('puestos');
        }

        $formAction = 'puestos-editar';
        $mensaje = '';
        $nombredelpuesto = (string)($puesto['Nombredelpuesto'] ?? '');
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

    public function positionsEdit()
    {
        $this->requireLogin();
        if (!$this->hasValidCsrfToken($_POST)) {
            Flash::set('Solicitud inválida, recargue la página e intente nuevamente.', 'error');
            $this->redirect('puestos');
        }

        $positionController = PositionController::fromEnvironment();
        $txtID = (int)($_POST['txtID'] ?? 0);
        $result = $positionController->updatePosition($txtID, $_POST);
        if (($result['success'] ?? false) === true) {
            Flash::set((string)($result['message'] ?? 'Registro actualizado'));
            $this->redirect('puestos');
        }

        $formAction = 'puestos-editar';
        $mensaje = (string)($result['message'] ?? 'No se pudo actualizar el registro.');
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

    public function positionsDelete()
    {
        $this->requireLogin();
        if (!$this->hasValidCsrfToken($_POST)) {
            Flash::set('Solicitud inválida, recargue la página e intente nuevamente.', 'error');
            $this->redirect('puestos');
        }

        $positionController = PositionController::fromEnvironment();
        $txtID = (int)($_POST['txtID'] ?? 0);
        if ($txtID > 0) {
            $deleted = $positionController->deletePosition($txtID);
            Flash::set($deleted ? 'Registro borrado' : 'No se pudo borrar el registro', $deleted ? 'success' : 'error');
        } else {
            Flash::set('El ID del puesto no es válido.', 'error');
        }

        $this->redirect('puestos');
    }

    public function usersIndex()
    {
        $this->requireLogin();
        $this->requireAdmin();
        $userController = UserController::fromEnvironment();

        $lista_tbl_usuarios = $userController->listUsers();
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

    public function usersCreateForm()
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

    public function usersCreate()
    {
        $this->requireLogin();
        $this->requireAdmin();
        if (!$this->hasValidCsrfToken($_POST)) {
            Flash::set('Solicitud inválida, recargue la página e intente nuevamente.', 'error');
            $this->redirect('usuarios-crear');
        }

        $userController = UserController::fromEnvironment();
        $result = $userController->createUser($_POST);
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

    public function usersEditForm()
    {
        $this->requireLogin();
        $this->requireAdmin();
        $userController = UserController::fromEnvironment();
        $txtID = (int)($_GET['txtID'] ?? 0);
        $usuarioData = $userController->getUser($txtID);
        if ($usuarioData === null) {
            Flash::set('No se encontró el usuario a editar.', 'error');
            $this->redirect('usuarios');
        }

        $formAction = 'usuarios-editar';
        $mensaje = '';
        $usuario = (string)($usuarioData['Nombreusuario'] ?? '');
        $correo = (string)($usuarioData['Correo'] ?? '');
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

    public function usersEdit()
    {
        $this->requireLogin();
        $this->requireAdmin();
        if (!$this->hasValidCsrfToken($_POST)) {
            Flash::set('Solicitud inválida, recargue la página e intente nuevamente.', 'error');
            $this->redirect('usuarios');
        }

        $userController = UserController::fromEnvironment();
        $txtID = (int)($_POST['txtID'] ?? 0);
        $result = $userController->updateUser($txtID, $_POST);
        if (($result['success'] ?? false) === true) {
            Flash::set((string)($result['message'] ?? 'Registro actualizado'));
            $this->redirect('usuarios');
        }

        $formAction = 'usuarios-editar';
        $mensaje = (string)($result['message'] ?? 'No se pudo actualizar el registro.');
        $usuario = trim((string)($_POST['usuario'] ?? ''));
        $correo = trim((string)($_POST['correo'] ?? ''));
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

    public function usersDelete()
    {
        $this->requireLogin();
        $this->requireAdmin();
        if (!$this->hasValidCsrfToken($_POST)) {
            Flash::set('Solicitud inválida, recargue la página e intente nuevamente.', 'error');
            $this->redirect('usuarios');
        }

        $userController = UserController::fromEnvironment();
        $txtID = (int)($_POST['txtID'] ?? 0);
        if ($txtID > 0) {
            $deleted = $userController->deleteUser($txtID);
            Flash::set($deleted ? 'Registro borrado' : 'No se pudo borrar el registro', $deleted ? 'success' : 'error');
        } else {
            Flash::set('El ID del usuario no es válido.', 'error');
        }

        $this->redirect('usuarios');
    }

    private function renderWithLayout($viewFile, array $data = [])
    {
        $data = array_merge([
            'pageHeaderTitle' => '',
            'pageHeaderIcon' => '',
            'pageBreadcrumbs' => [],
        ], $data);
        $csrfToken = Security::getCsrfToken();
        extract($data);
        require $this->projectRoot . '/app/Views/layout/header.php';
        require $this->projectRoot . '/app/Views/layout/module_header.php';
        require $this->projectRoot . '/app/Views/' . ltrim((string)$viewFile, '/');
        require $this->projectRoot . '/app/Views/layout/footer.php';
    }

    private function pageHeaderData(string $title, string $icon, array $breadcrumbs): array
    {
        return [
            'pageHeaderTitle' => $title,
            'pageHeaderIcon' => $icon,
            'pageBreadcrumbs' => $breadcrumbs,
        ];
    }

    private function moduleBreadcrumbs(
        string $moduleLabel,
        string $moduleRoute,
        string $moduleIcon,
        string $currentLabel = '',
        string $currentIcon = ''
    ): array {
        $breadcrumbs = [[
            'label' => 'Inicio',
            'href' => $this->publicBaseUrl,
            'icon' => 'fas fa-house',
        ]];

        if ($currentLabel === '') {
            $breadcrumbs[] = [
                'label' => $moduleLabel,
                'icon' => $moduleIcon,
                'active' => true,
            ];
            return $breadcrumbs;
        }

        $breadcrumbs[] = [
            'label' => $moduleLabel,
            'href' => $this->publicBaseUrl . ltrim($moduleRoute, '/'),
            'icon' => $moduleIcon,
        ];

        $breadcrumbs[] = [
            'label' => $currentLabel,
            'icon' => $currentIcon,
            'active' => true,
        ];

        return $breadcrumbs;
    }

    private function requireLogin()
    {
        (new AuthMiddleware())->requireLogin($this->publicBaseUrl . 'login');
    }

    private function currentUser()
    {
        return (new AuthMiddleware())->currentUser();
    }

    private function requireAdmin()
    {
        if ($this->currentUser() !== 'Administrador') {
            Flash::set('No tiene permisos para acceder a esta sección.', 'error');
            $this->redirect('');
        }
    }

    private function redirect($route = '')
    {
        header('Location:' . $this->publicBaseUrl . ltrim((string)$route, '/'));
        exit();
    }

    private function hasValidCsrfToken(array $request): bool
    {
        return Security::isValidCsrfToken($request['csrf_token'] ?? null);
    }
}
