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
$mensaje = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $resultado = $controller->createPosition($_POST);
    if (isset($resultado['success']) && $resultado['success'] === true) {
        Flash::set('Registro Agregado', 'success');
        header('Location:index.php');
        exit();
    }
    $mensaje = isset($resultado['message']) ? $resultado['message'] : 'No se pudo agregar el registro.';
}

$formAction = 'crear.php';

?>

<?php include("../../templates/header.php") ?>
<?php require __DIR__ . '/../../app/Views/positions/create.php'; ?>
<?php include("../../templates/footer.php") ?>
