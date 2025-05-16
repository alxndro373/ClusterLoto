<?php
require_once __DIR__ . '/../models/modeloPagos.php';
require_once __DIR__ . '/../models/modeloEgreso.php';

session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'comite') {
    header("Location: /auth/login.php");
    exit();
}

$reporte = null;
$tipo = '';
$anio = '';

$meses = [
'01' => 'enero', '02' => 'febrero', '03' => 'marzo', '04' => 'abril',
'05' => 'mayo', '06' => 'junio', '07' => 'julio', '08' => 'agosto',
'09' => 'septiembre', '10' => 'octubre', '11' => 'noviembre', '12' => 'diciembre'
];

$mesActual = date('m');
$mesNombre = $meses[$mesActual];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tipo = $_POST['tipo'];
    $anio = $_POST['anio'];

    if ($tipo === 'mensual' && !empty($_POST['mes'])) {
        $mes = $_POST['mes'];
        $reporte = PagoModel::obtenerResumenMensual($anio, $mes);
    } elseif ($tipo === 'anual') {
        $reporte = PagoModel::obtenerResumenAnual($anio);
    }
}