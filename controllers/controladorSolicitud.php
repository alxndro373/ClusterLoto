<?php
require_once __DIR__ . '/../models/modeloSolicitud.php';

session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'inquilino') {
    header("Location: /auth/login.php");
    exit();
}

$mensaje = "";
$idUsuario = $_SESSION['idUsuario'];
$casa = SolicitudModel::obtenerCasasPorUsuario($idUsuario);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $casa) {
    $tipo = $_POST['tipo'];
    $fechaHoy = date("Y-m-d");
    $idCasa = $casa['idCasa'];

    if ($tipo === 'reserva') {
        $espacio = $_POST['espacio'];
        $fechaReserva = $_POST['fecha_reserva'];

        if (!$espacio || !$fechaReserva) {
            $mensaje = "Todos los campos de la reserva son obligatorios.";
        } elseif (strtotime($fechaReserva) < strtotime($fechaHoy)) {
            $mensaje = "No puedes seleccionar una fecha pasada.";
        } elseif (SolicitudModel::reservaYaExistente($espacio, $fechaReserva, $idCasa)) {
            $mensaje = "Ya existe una reserva para ese espacio y fecha.";
        } else {
            $descripcion = "Reserva de $espacio para el " . date("d-m-Y", strtotime($fechaReserva));
            if (SolicitudModel::registrar($idCasa, 'reserva', $descripcion, $fechaHoy)) {
                $mensaje = "Reserva registrada correctamente.";
            } else {
                $mensaje = "Error al registrar la reserva.";
            }
        }
    } elseif ($tipo === 'servicio') {
        $descripcion = trim($_POST['descripcion']);
        if (empty($descripcion)) {
            $mensaje = "La descripción del servicio es obligatoria.";
        } else {
            if (SolicitudModel::registrar($idCasa, 'servicio', $descripcion, $fechaHoy)) {
                $mensaje = "Solicitud de servicio registrada correctamente.";
            } else {
                $mensaje = "Error al registrar la solicitud.";
            }
        }
    }
}

$foroSolicitudes = SolicitudModel::obtenerSolicitudesVisibles();