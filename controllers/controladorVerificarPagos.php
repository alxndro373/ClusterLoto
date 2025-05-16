<?php
require_once __DIR__ . '/../models/modeloPagos.php';
require_once __DIR__ . '/../includes/enviar_acuse.php';

session_start();
$mensaje = "";
if (isset($_SESSION['mensaje'])) {
    $mensaje = $_SESSION['mensaje'];
    unset($_SESSION['mensaje']);
}
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'comite') {
    header("Location: /../auth/login.php");
    exit();
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['idPago'], $_POST['accion'], $_POST['csrf_token'])) {
    if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Token CSRF inválido");
    }

    $idPago = $_POST['idPago'];
    $accion = $_POST['accion'];
    $datos = PagoModel::obtenerInfoPago($idPago);

    if (!$datos) {
        $mensaje = "No se pudo obtener información del pago.";
    } elseif ($accion === 'verificar' && PagoModel::verificarPago($idPago)) {
        error_log("Enviando acuse a: " . $datos['email']);
        enviarAcuse($idPago, $datos['email'], $datos['nombre'], $datos['concepto'], $datos['monto'], $datos['recargo']);
        $_SESSION['mensaje'] = "El pago con ID $idPago fue verificado y se envió el acuse.";
        header("Location: verificar_Pagos.php");
        exit();
    } elseif ($accion === 'rechazar' && PagoModel::rechazarPago($idPago)) {
        error_log("Enviando acuse de rechazo a: " . $datos['email']);
        enviarAcuseRechazo($idPago, $datos['email'], $datos['nombre'], $datos['concepto'], $datos['monto'], $datos['recargo']);
        $mensaje = "El pago con ID $idPago fue rechazado y se envió el acuse.";
    } else {
        $mensaje = "Error al procesar el pago con ID $idPago.";
    }
}

$pagosPendientes = PagoModel::obtenerPagosNoVerificados();
