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
$mensaje = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $resultado = $controller->createEmployee($_POST, $_FILES, __DIR__);
  if (isset($resultado['success']) && $resultado['success'] === true) {
    Flash::set('Registro Agregado', 'success');
    header('Location:index.php');
    exit();
  }
  $mensaje = isset($resultado['message']) ? $resultado['message'] : 'No se pudo agregar el registro.';
}

$lista_tbl_puestos = $controller->listPositions();
$formAction = 'crear.php';
?>

<?php include("../../templates/header.php") ?>
<?php require __DIR__ . '/../../app/Views/employees/create.php'; ?>

<?php include("../../templates/footer.php") ?>
