<?php
include("../../bd.php");

if (isset($_GET["txtID"])) {
    $txtID = (isset($_GET["txtID"])) ? $_GET["txtID"] : "";

    $sentencia = $conexion->prepare("SELECT *,(SELECT Nombredelpuesto FROM `tbl-puestos` WHERE ID=Idpuesto limit 1) as puesto FROM `tbl-empleados` WHERE ID=:ID");
    $sentencia->bindParam(":ID", $txtID);
    $sentencia->execute();
    $registro = $sentencia->fetch(PDO::FETCH_LAZY);

    $primernombre = $registro["Primernombre"];
    $segundonombre = $registro["Segundonombre"];
    $primerapellido = $registro["Primerapellido"];
    $segundoapellido = $registro["Segundoapellido"];

    $nombreCompleto = $primernombre . " " . $segundonombre . " " . $primerapellido . " " . $segundoapellido;
    $fechaActual = date('d/m/Y');

    $foto = $registro["Foto"];
    $cv = $registro["CV"];
    $idpuesto = $registro["Idpuesto"];
    $puesto = $registro["puesto"];
    $fechadeingreso = $registro["Fecha"];

    $fechaInicio = new DateTime($fechadeingreso);
    $fechaFin = new DateTime(date('Y/m/d'));
    $diferencia = date_diff($fechaInicio, $fechaFin);
}
ob_start();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carta de recomendación</title>
</head>

<body>
    <h1>Carta de Recomendación Laboral</h1>
    <p>
        Santa Cruz de la Sierra, Bolivia a <strong><?php echo $fechaActual; ?></strong>.
        <br><br>
        A quien pueda interesarle:
        <br><br>
        Reciba un cordial y respetuoso saludo.
        <br><br>
        A través de estas líneas deseo hacer de su conocimiento que Sr(a) <strong><?php echo $nombreCompleto; ?></strong>,
        quien laboro en mi organización durante <strong><?php echo $diferencia->y; ?> año(s)</strong>
        es un ciudadano con una conducta intachable. Ha demostrado ser un gran trabajador,
        comprometido, responsable y fiel cumplidor de sus tareas.
        Siempre ha manifestado preocupación por mejorar, capacitarse y actualizar sus conocimientos.
        <br><br>
        Durante estos años se ha desempeñado como: <strong><?php echo $puesto; ?>.</strong>
        Es por ello le sugiero considere esta recomendación, con la confianza de que estará siempre a la altura de sus compromisos y responsabilidades.
        <br><br>
        Sin más nada a que referirme y, esperando que esta misiva sea tomada en cuenta, dejo mi número de contacto para cualquier información de interés.
        <br><br><br><br><br><br><br><br>
        _____________________ <br>
        Atentamente
        <br>
        Ing. Juan Pablo Perez.
    </p>

</body>

</html>

<?php
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