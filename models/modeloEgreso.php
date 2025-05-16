<?php
require_once __DIR__ . '/../db/conexion.php';

class EgresoModel {
    public static function registrar($fecha, $monto, $motivo, $pagado_a, $registrado_por) {
        global $conn;
        $stmt = $conn->prepare("INSERT INTO egresos (fecha, monto, motivo, pagado_a, registrado_por) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sdssi", $fecha, $monto, $motivo, $pagado_a, $registrado_por);
        return $stmt->execute();
    }

    public static function obtenerTodos() {
        global $conn;
        $sql = "
            SELECT e.fecha, e.monto, e.motivo, e.pagado_a, u.nombre, u.cargo
            FROM egresos e
            JOIN usuario u ON e.registrado_por = u.idUsuario
            ORDER BY e.fecha DESC
        ";
        return $conn->query($sql);
    }

    public static function obtenerEgresosPorPeriodo($fechaInicio, $fechaFin) {
        global $conn;
        $stmt = $conn->prepare("SELECT SUM(monto) AS total FROM egresos WHERE fecha BETWEEN ? AND ?");
        $stmt->bind_param("ss", $fechaInicio, $fechaFin);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc()['total'] ?? 0;
    }
}
