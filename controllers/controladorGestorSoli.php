<?php
require_once __DIR__ . '/../models/modeloSolicitud.php';

session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'comite') {
    header("Location: /auth/login.php");
    exit();
}

$mensaje = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['idSolicitud'])) {
    $id = $_POST['idSolicitud'];
    $comentario = trim($_POST['comentario']);

    if (SolicitudModel::actualizarEstado($id, $comentario)) {
        $mensaje = "Solicitud marcada como atendida.";
    } else {
        $mensaje = "Error al actualizar la solicitud.";
    }
}

$solicitudes = SolicitudModel::obtenerTodas();