<?php
require_once __DIR__ . '/../models/modeloEgreso.php';

session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'comite') {
    header("Location: /../auth/login.php");
    exit();
}

$mensaje = "";
$mensaje_error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $mensaje_error = "Token CSRF no válido. La solicitud no es segura.";
    } else {
        $fecha = $_POST['fecha'];
        $monto = $_POST['monto'];
        $motivo = $_POST['motivo'];
        $pagado_a = $_POST['pagado_a'];
        $registrado_por = $_SESSION['idUsuario'];

        if (EgresoModel::registrar($fecha, $monto, $motivo, $pagado_a, $registrado_por)) {
            $mensaje = "Egreso registrado correctamente.";
        } else {
            $mensaje_error = "Error al registrar egreso.";
        }
    }
}

$egresos = EgresoModel::obtenerTodos();