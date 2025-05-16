<?php
require_once __DIR__ . '/../models/modeloEstadoCuenta.php';
require_once __DIR__ . '/../models/modeloUsuario.php';

session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'comite') {
    header("Location: /auth/login.php");
    exit();
}

$estadoCuenta = [];
$totalIngresos = 0;
$totalEgresos = 0;

$meses = [
'01' => 'Enero', '02' => 'Febrero', '03' => 'Marzo', '04' => 'Abril',
'05' => 'Mayo', '06' => 'Junio', '07' => 'Julio', '08' => 'Agosto',
'09' => 'Septiembre', '10' => 'Octubre', '11' => 'Noviembre', '12' => 'Diciembre'
];

$mesActual = date('m');
$anioActual = date('Y');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificar el token CSRF
    if (isset($_POST['csrf_token']) && $_POST['csrf_token'] === $_SESSION['csrf_token']) {
        $mes = $_POST['mes'];
        $anio = $_POST['anio'];
        $idUsuario = !empty($_POST['idUsuario']) ? $_POST['idUsuario'] : null;

        $pagos = EstadoCuentaModel::obtenerPagosPorPeriodo($mes, $anio, $idUsuario);
        $egresos = EstadoCuentaModel::obtenerEgresosPorPeriodo($mes, $anio);

        while ($p = $pagos->fetch_assoc()) {
            $estadoCuenta['pagos'][] = $p;
            $totalIngresos += $p['monto'] + $p['recargo'];
        }

        while ($e = $egresos->fetch_assoc()) {
            $estadoCuenta['egresos'][] = $e;
            $totalEgresos += $e['monto'];
        }
    } else {
        // Token CSRF no válido
        die("Error: Token CSRF inválido. La solicitud ha sido rechazada.");
    }
}