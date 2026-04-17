<?php

require_once __DIR__ . '/../../core/Env.php';
require_once __DIR__ . '/../../core/Flash.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../app/Repositories/PositionRepository.php';
require_once __DIR__ . '/../../app/Services/PositionService.php';
require_once __DIR__ . '/../../app/Controllers/PositionController.php';

use App\Controllers\PositionController;
use Core\Env;
use Core\Flash;

Env::load(__DIR__ . '/../../.env');
$controller = PositionController::fromEnvironment();

$txtID = isset($_GET["txtID"]) ? (int)$_GET["txtID"] : (isset($_POST["txtID"]) ? (int)$_POST["txtID"] : 0);
$mensaje = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $resultado = $controller->updatePosition($txtID, $_POST);
    if (isset($resultado['success']) && $resultado['success'] === true) {
        Flash::set('Registro Actualizado', 'success');
        header("Location:index.php");
        exit();
    }
    $mensaje = isset($resultado['message']) ? $resultado['message'] : 'No se pudo actualizar el registro.';
}

$registro = $controller->getPosition($txtID);
if ($registro === null && $txtID > 0 && $mensaje === null) {
    $mensaje = 'No se encontró el puesto a editar.';
}

$nombredelpuesto = isset($registro["Nombredelpuesto"]) ? $registro["Nombredelpuesto"] : "";
$formAction = 'editar.php?txtID=' . $txtID;
?>

<?php include("../../templates/header.php") ?>
<?php require __DIR__ . '/../../app/Views/positions/edit.php'; ?>
<?php include("../../templates/footer.php") ?>
