<?php
require_once __DIR__ . '/../db/conexion.php';

class EstadoCuentaModel {
    public static function obtenerPagosPorPeriodo($mes, $anio, $idUsuario = null) {
        global $conn;
        $fechaInicio = "$anio-$mes-01";
        $fechaFin = date("Y-m-t", strtotime($fechaInicio));

        $sql = "
            SELECT p.fechaPago, p.monto, p.recargo, p.concepto, u.nombre,
                   GROUP_CONCAT(DISTINCT c.numero ORDER BY c.numero SEPARATOR ', ') AS numeroCasa
            FROM pagos p
            JOIN usuario u ON p.idUsuario = u.idUsuario
            LEFT JOIN usuario_casa uc ON u.idUsuario = uc.idUsuario
            LEFT JOIN casas c ON uc.idCasa = c.idCasa
            WHERE p.verificado = 1 AND p.fechaPago BETWEEN ? AND ?
        ";

        if ($idUsuario) {
            $sql .= " AND p.idUsuario = ?";
        }

        $sql .= " GROUP BY p.idPago";

        $stmt = $conn->prepare($idUsuario ? "$sql" : substr($sql, 0, -17));
        if ($idUsuario) {
            $stmt->bind_param("ssi", $fechaInicio, $fechaFin, $idUsuario);
        } else {
            $stmt->bind_param("ss", $fechaInicio, $fechaFin);
        }

        $stmt->execute();
        return $stmt->get_result();
    }

    public static function obtenerEgresosPorPeriodo($mes, $anio) {
        global $conn;
        $fechaInicio = "$anio-$mes-01";
        $fechaFin = date("Y-m-t", strtotime($fechaInicio));

        $stmt = $conn->prepare("SELECT * FROM egresos WHERE fecha BETWEEN ? AND ?");
        $stmt->bind_param("ss", $fechaInicio, $fechaFin);
        $stmt->execute();
        return $stmt->get_result();
    }
}
