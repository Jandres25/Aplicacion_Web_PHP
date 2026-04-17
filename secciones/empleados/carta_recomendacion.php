<?php
require_once __DIR__ . '/../../core/Env.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../app/Infrastructure/EmployeeFileStorage.php';
require_once __DIR__ . '/../../app/Repositories/EmployeeRepository.php';
require_once __DIR__ . '/../../app/Services/EmployeeService.php';
require_once __DIR__ . '/../../app/Controllers/EmployeeController.php';

use App\Controllers\EmployeeController;
use Core\Env;

Env::load(__DIR__ . '/../../.env');
$controller = EmployeeController::fromEnvironment();

$txtID = isset($_GET["txtID"]) ? (int)$_GET["txtID"] : 0;
$registro = $controller->getEmployeeWithPosition($txtID);

$primernombre = isset($registro["Primernombre"]) ? $registro["Primernombre"] : '';
$segundonombre = isset($registro["Segundonombre"]) ? $registro["Segundonombre"] : '';
$primerapellido = isset($registro["Primerapellido"]) ? $registro["Primerapellido"] : '';
$segundoapellido = isset($registro["Segundoapellido"]) ? $registro["Segundoapellido"] : '';
$nombreCompleto = trim($primernombre . " " . $segundonombre . " " . $primerapellido . " " . $segundoapellido);
$fechaActual = date('d/m/Y');
$puesto = isset($registro["puesto"]) ? $registro["puesto"] : '';
$fechadeingreso = isset($registro["Fecha"]) ? $registro["Fecha"] : date('Y-m-d');

$fechaInicio = new DateTime($fechadeingreso);
$fechaFin = new DateTime(date('Y/m/d'));
$diferencia = date_diff($fechaInicio, $fechaFin);
ob_start();
require __DIR__ . '/../../app/Views/employees/recommendation_letter.php';
$HTML = ob_get_clean();

require_once("../../libs/dompdf/autoload.inc.php");

use Dompdf\Dompdf;

$dompdf = new Dompdf();

$opciones = $dompdf->getOptions();
$opciones->set(array("isRemoteEnabled" => true));

$dompdf->setOptions($opciones);

$dompdf->loadHtml($HTML);

$dompdf->setPaper("letter");
$dompdf->render();
$dompdf->stream("archivo.pdf", array("Attachment" => false));

?>
