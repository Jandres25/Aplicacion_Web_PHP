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

if (isset($_GET['txtID'])) {
    $controller->deletePosition($_GET['txtID']);
    Flash::set('Registro Eliminado', 'success');
    header('Location:index.php');
    exit();
}

$lista_tbl_puestos = $controller->listPositions();

?>

<?php include("../../templates/header.php"); ?>
<?php require __DIR__ . '/../../app/Views/positions/index.php'; ?>
<?php include("../../templates/footer.php"); ?>
