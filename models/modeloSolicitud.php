<?php
require_once __DIR__ . '/../db/conexion.php';

class SolicitudModel {
    public static function registrar($idCasa, $tipo, $descripcion, $fecha) {
        global $conn;
        $stmt = $conn->prepare("INSERT INTO solicitudes (idCasa, tipo, descripcion, fechaSolicitud, visibleSoli) VALUES (?, ?, ?, ?, 1)");
        $stmt->bind_param("isss", $idCasa, $tipo, $descripcion, $fecha);
        return $stmt->execute();
    }

    public static function reservaYaExistente($espacio, $fecha, $idCasa) {
        global $conn;
        $like = "%$espacio%$fecha%";
        $stmt = $conn->prepare("
            SELECT COUNT(*) AS total FROM solicitudes
            WHERE tipo = 'reserva' AND descripcion LIKE ? AND idCasa = ?
        ");
        $stmt->bind_param("si", $like, $idCasa);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc()['total'] > 0;
    }

    public static function obtenerCasasPorUsuario($idUsuario) {
        global $conn;
        $stmt = $conn->prepare("
            SELECT c.idCasa, c.numero
            FROM usuario_casa uc
            JOIN casas c ON uc.idCasa = c.idCasa
            WHERE uc.idUsuario = ?
            LIMIT 1
        ");
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public static function obtenerSolicitudesUsuario($idCasa) {
        global $conn;
        $stmt = $conn->prepare("
            SELECT * FROM solicitudes
            WHERE idCasa = ?
            ORDER BY fechaSolicitud DESC
        ");
        $stmt->bind_param("i", $idCasa);
        $stmt->execute();
        return $stmt->get_result();
    }

    public static function obtenerTodas() {
        global $conn;
        $sql = "
            SELECT s.idSolicitud, c.numero AS numeroCasa, s.tipo, s.descripcion, s.fechaSolicitud, s.atendido, s.comentario
            FROM solicitudes s
            JOIN casas c ON s.idCasa = c.idCasa
            ORDER BY s.fechaSolicitud DESC
        ";
        return $conn->query($sql);
    }

    public static function actualizarEstado($idSolicitud, $comentario) {
        global $conn;
        $stmt = $conn->prepare("
            UPDATE solicitudes SET atendido = 1, comentario = ? WHERE idSolicitud = ?
        ");
        $stmt->bind_param("si", $comentario, $idSolicitud);
        return $stmt->execute();
    }

    public static function obtenerSolicitudesVisibles() {
        global $conn;
        $sql = "
            SELECT s.idSolicitud, c.numero AS numeroCasa, s.tipo, s.descripcion, s.fechaSolicitud, s.atendido, s.comentario 
            FROM solicitudes s
            JOIN casas c ON s.idCasa = c.idCasa
            WHERE s.visibleSoli = 1
            ORDER BY s.fechaSolicitud DESC
        ";
        return $conn->query($sql);
    }
}