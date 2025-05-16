<?php
require_once __DIR__ . '/../db/conexion.php';

class PagoModel {
    public static function obtenerPagoMes($idUsuario) {
        global $conn;
        $stmt = $conn->prepare("
            SELECT verificado FROM pagos 
            WHERE idUsuario = ? 
            AND MONTH(fechaPago) = MONTH(CURDATE()) 
            AND YEAR(fechaPago) = YEAR(CURDATE())
            LIMIT 1
        ");
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public static function registrarPago($idUsuario, $idCasa, $fecha, $monto, $recargo, $concepto, $comprobante) {
        global $conn;
        $stmt = $conn->prepare("
            INSERT INTO pagos (idUsuario, idCasa, fechaPago, monto, recargo, concepto, comprobante)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param("iissdss", $idUsuario, $idCasa, $fecha, $monto, $recargo, $concepto, $comprobante);
        return $stmt->execute();
    }

    public static function obtenerHistorial($idUsuario) {
        global $conn;
        $stmt = $conn->prepare("
            SELECT fechaPago, monto, recargo, concepto, verificado
            FROM pagos
            WHERE idUsuario = ?
            ORDER BY fechaPago DESC
        ");
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();
        return $stmt->get_result();
    }

    public static function obtenerPagosNoVerificados() {
        global $conn;
        $sql = "
            SELECT p.idPago, p.fechaPago, p.monto, p.recargo, p.concepto, u.nombre, 
            GROUP_CONCAT(DISTINCT c.numero ORDER BY c.numero SEPARATOR ', ') AS numeroCasa
            FROM pagos p
            JOIN usuario u ON p.idUsuario = u.idUsuario
            LEFT JOIN usuario_casa uc ON u.idUsuario = uc.idUsuario
            LEFT JOIN casas c ON uc.idCasa = c.idCasa
            WHERE p.verificado = 0
            GROUP BY p.idPago, p.fechaPago, p.monto, p.recargo, p.concepto, u.nombre
            ORDER BY p.fechaPago DESC
        ";
        return $conn->query($sql);
    }
    
    public static function verificarPago($idPago) {
        global $conn;
        $stmt = $conn->prepare("
        UPDATE pagos SET verificado = 1 WHERE idPago = ?");
        $stmt->bind_param("i", $idPago);
        return $stmt->execute();
    }

    public static function rechazarPago($idPago) {
        global $conn;
        $stmt = $conn->prepare("
        DELETE FROM pagos WHERE idPago = ?
        ");
        $stmt->bind_param("i", $idPago);
        return $stmt->execute();
    }
    
    public static function obtenerInfoPago($idPago) {
        global $conn;
        $sql = "
            SELECT u.email, u.nombre, p.concepto, p.monto, p.recargo
            FROM pagos p
            JOIN usuario u ON p.idUsuario = u.idUsuario
            WHERE p.idPago = ?
        ";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $idPago);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public static function obtenerPagosPorPeriodo($fechaInicio, $fechaFin, $idUsuario = null) {
        global $conn;
    
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
    
        $stmt = $conn->prepare($sql);
    
        if ($idUsuario) {
            $stmt->bind_param("ssi", $fechaInicio, $fechaFin, $idUsuario);
        } else {
            $stmt->bind_param("ss", $fechaInicio, $fechaFin);
        }
    
        $stmt->execute();
        return $stmt->get_result();
    }

    public static function obtenerResumenMensual($anio, $mes) {
        $fechaInicio = "$anio-$mes-01";
        $fechaFin = date("Y-m-t", strtotime($fechaInicio));
    
        $ingresosResult = self::obtenerPagosPorPeriodo($fechaInicio, $fechaFin);
        $ingresos = 0;
        while ($row = $ingresosResult->fetch_assoc()) {
            $ingresos += $row['monto'] + $row['recargo'];
        }
    
        $egresos = EgresoModel::obtenerEgresosPorPeriodo($fechaInicio, $fechaFin);
    
        return [
            'ingresos' => $ingresos,
            'egresos' => $egresos,
            'saldo' => $ingresos - $egresos
        ];
    }

    public static function obtenerResumenAnual($anio) {
        $fechaInicio = "$anio-01-01";
        $fechaFin = "$anio-12-31";
    
        $ingresosResult = self::obtenerPagosPorPeriodo($fechaInicio, $fechaFin);
        $ingresos = 0;
        while ($row = $ingresosResult->fetch_assoc()) {
            $ingresos += $row['monto'] + $row['recargo'];
        }
    
        $egresos = EgresoModel::obtenerEgresosPorPeriodo($fechaInicio, $fechaFin);
    
        return [
            'ingresos' => $ingresos,
            'egresos' => $egresos,
            'saldo' => $ingresos - $egresos
        ];
    }

    public static function casaYaPagadaEsteMes($idCasa) {
        global $conn;
        $stmt = $conn->prepare("
            SELECT COUNT(*) as total FROM pagos p
            JOIN usuario u ON p.idUsuario = u.idUsuario
            JOIN usuario_casa uc ON u.idUsuario = uc.idUsuario
            WHERE uc.idCasa = ? 
            AND MONTH(p.fechaPago) = MONTH(CURDATE()) 
            AND YEAR(p.fechaPago) = YEAR(CURDATE())
        ");
        $stmt->bind_param("i", $idCasa);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        return $res['total'] > 0;
    }

    public static function obtenerEstadoPagoCasaMesActual($idUsuario) {
        global $conn;
        $stmt = $conn->prepare("
            SELECT p.verificado
            FROM pagos p
            JOIN usuario u ON p.idUsuario = u.idUsuario
            JOIN usuario_casa uc ON u.idUsuario = uc.idUsuario
            WHERE uc.idCasa IN (
                SELECT idCasa FROM usuario_casa WHERE idUsuario = ?
            )
            AND MONTH(p.fechaPago) = MONTH(CURDATE())
            AND YEAR(p.fechaPago) = YEAR(CURDATE())
            ORDER BY p.fechaPago DESC
            LIMIT 1
        ");
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        return $res ? ['verificado' => $res['verificado']] : null;
    }

    public static function actualizarComprobante($idPago, $rutaPDF) {
        global $conn;
        $stmt = $conn->prepare("UPDATE pagos SET comprobante = ? WHERE idPago = ?");
        $stmt->bind_param("si", $rutaPDF, $idPago);
        return $stmt->execute();
    }
    
}
