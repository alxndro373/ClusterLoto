<?php
require_once __DIR__ . '/../models/modeloPagos.php';
require_once __DIR__ . '/../models/modeloUsuario.php';

session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'inquilino') {
    header("Location: /../auth/login.php");
    exit();
}

$mensaje = "";
$CUOTA = 650;
$RECARGO = 50;

$meses = [
    '01' => 'enero', '02' => 'febrero', '03' => 'marzo', '04' => 'abril',
    '05' => 'mayo', '06' => 'junio', '07' => 'julio', '08' => 'agosto',
    '09' => 'septiembre', '10' => 'octubre', '11' => 'noviembre', '12' => 'diciembre'
];

$mesActual = date("m");
$anioActual = date("Y");
$mesNombre = $meses[$mesActual];

$idUsuario = $_SESSION['idUsuario'];
$pagoMesActual = PagoModel::obtenerEstadoPagoCasaMesActual($idUsuario);
$casas = UsuarioModel::obtenerCasasPorUsuario($idUsuario);
$sinCasas = ($casas->num_rows === 0);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['idCasa'])) {
    $idCasa = intval($_POST['idCasa']);

    if (!PagoModel::casaYaPagadaEsteMes($idCasa)) {
        $fecha = date("Y-m-d");
        $recargo = (date("d") > 28) ? $RECARGO : 0;
        $numeroCasa = htmlspecialchars($_POST['numeroCasa']);
        $concepto = "Pago de la cuota de $mesNombre del $anioActual para casa #$numeroCasa";
        $comprobante = "sin comprobante";

        if (PagoModel::registrarPago($idUsuario, $idCasa, $fecha, $CUOTA, $recargo, $concepto, $comprobante)) {
            $mensaje = "Pago registrado correctamente para casa #$numeroCasa.";
        } else {
            $mensaje = "Error al registrar el pago.";
        }
    } else {
        $mensaje = "Esta casa ya tiene un pago registrado este mes.";
    }
}

$historialPagos = PagoModel::obtenerHistorial($idUsuario);