<?php

require_once __DIR__ . '/../../core/Env.php';
require_once __DIR__ . '/../../core/Flash.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../app/Repositories/UserRepository.php';
require_once __DIR__ . '/../../app/Services/UserService.php';
require_once __DIR__ . '/../../app/Controllers/UserController.php';

use App\Controllers\UserController;
use Core\Env;
use Core\Flash;

Env::load(__DIR__ . '/../../.env');
$controller = UserController::fromEnvironment();

if (isset($_GET['txtID'])) {
    $controller->deleteUser($_GET['txtID']);
    Flash::set('Registro Eliminado', 'success');
    header("Location:index.php");
    exit();
}
$lista_tbl_usuarios = $controller->listUsers();

?>

<?php include("../../templates/header.php"); ?>
<?php require __DIR__ . '/../../app/Views/users/index.php'; ?>
<?php include("../../templates/footer.php"); ?>
