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

$txtID = isset($_GET["txtID"]) ? (int)$_GET["txtID"] : (isset($_POST["txtID"]) ? (int)$_POST["txtID"] : 0);
$mensaje = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $resultado = $controller->updateUser($txtID, $_POST);
    if (isset($resultado['success']) && $resultado['success'] === true) {
        Flash::set('Registro Actualizado', 'success');
        header("Location:index.php");
        exit();
    }
    $mensaje = isset($resultado['message']) ? $resultado['message'] : 'No se pudo actualizar el registro.';
}

$registro = $controller->getUser($txtID);
if ($registro === null && $txtID > 0 && $mensaje === null) {
    $mensaje = 'No se encontró el usuario a editar.';
}

$usuario = isset($registro["Nombreusuario"]) ? $registro["Nombreusuario"] : "";
$password = isset($registro["Password"]) ? $registro["Password"] : "";
$correo = isset($registro["Correo"]) ? $registro["Correo"] : "";
$formAction = 'editar.php?txtID=' . $txtID;

?>

<?php include("../../templates/header.php") ?>
<?php require __DIR__ . '/../../app/Views/users/edit.php'; ?>
<?php include("../../templates/footer.php") ?>
