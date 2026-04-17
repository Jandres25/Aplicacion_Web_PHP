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

$txtID = isset($_GET["txtID"]) ? (int)$_GET["txtID"] : (isset($_POST["txtID"]) ? (int)$_POST["txtID"] : 0);
$mensaje = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $resultado = $controller->updateEmployee($txtID, $_POST, $_FILES, __DIR__);
    if (isset($resultado['success']) && $resultado['success'] === true) {
        Flash::set('Registro Actualizado', 'success');
        header("Location:index.php");
        exit();
    }
    $mensaje = isset($resultado['message']) ? $resultado['message'] : 'No se pudo actualizar el registro.';
}

$registro = $controller->getEmployee($txtID);
$lista_tbl_puestos = $controller->listPositions();

$primernombre = isset($registro["Primernombre"]) ? $registro["Primernombre"] : "";
$segundonombre = isset($registro["Segundonombre"]) ? $registro["Segundonombre"] : "";
$primerapellido = isset($registro["Primerapellido"]) ? $registro["Primerapellido"] : "";
$segundoapellido = isset($registro["Segundoapellido"]) ? $registro["Segundoapellido"] : "";
$foto = isset($registro["Foto"]) ? $registro["Foto"] : "";
$cv = isset($registro["CV"]) ? $registro["CV"] : "";
$idpuesto = isset($registro["Idpuesto"]) ? $registro["Idpuesto"] : "";
$fechadeingreso = isset($registro["Fecha"]) ? $registro["Fecha"] : "";
$formAction = 'editar.php?txtID=' . $txtID;
?>

<?php include("../../templates/header.php") ?>
<?php require __DIR__ . '/../../app/Views/employees/edit.php'; ?>
<?php include("../../templates/footer.php") ?>
