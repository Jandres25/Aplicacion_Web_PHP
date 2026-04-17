<?php

require_once __DIR__ . '/../../core/Env.php';
require_once __DIR__ . '/../../core/Flash.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../app/Infrastructure/EmployeeFileStorage.php';
require_once __DIR__ . '/../../app/Repositories/EmployeeRepository.php';
require_once __DIR__ . '/../../app/Services/EmployeeService.php';
require_once __DIR__ . '/../../app/Controllers/EmployeeController.php';

use App\Controllers\EmployeeController;
use Core\Env;
use Core\Flash;

Env::load(__DIR__ . '/../../.env');
$controller = EmployeeController::fromEnvironment();

if (isset($_GET['txtID'])) {
    $controller->deleteEmployee($_GET['txtID'], __DIR__);
    Flash::set('Registro Eliminado', 'success');
    header('Location:index.php');
    exit();
}

$lista_tbl_empleados = $controller->listEmployees();

?>

<?php include("../../templates/header.php"); ?>
<?php require __DIR__ . '/../../app/Views/employees/index.php'; ?>
<?php include("../../templates/footer.php"); ?>
