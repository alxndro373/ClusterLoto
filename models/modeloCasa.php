<?php
require_once __DIR__ . '/../db/conexion.php';

class CasaModel {
    public static function obtenerTodas() {
        global $conn;
        return $conn->query("SELECT * FROM casas ORDER BY numero ASC");
    }

    public static function agregar($numero) {
        global $conn;
        $stmt = $conn->prepare("INSERT INTO casas (numero) VALUES (?)");
        $stmt->bind_param("i", $numero);
        return $stmt->execute();
    }

    public static function eliminar($idCasa) {
        global $conn;
        $stmt = $conn->prepare("DELETE FROM casas WHERE idCasa = ?");
        $stmt->bind_param("i", $idCasa);
        return $stmt->execute();
    }

    public static function estaAsignada($idCasa) {
        global $conn;
        $stmt = $conn->prepare("SELECT COUNT(*) as total FROM usuario WHERE idCasa = ?");
        $stmt->bind_param("i", $idCasa);
        $stmt->execute();
        $resultado = $stmt->get_result()->fetch_assoc();
        return $resultado['total'] > 0;
    }

    public static function numeroExiste($numero) {
        global $conn;
        $stmt = $conn->prepare("SELECT COUNT(*) FROM casas WHERE numero = ?");
        $stmt->bind_param("s", $numero);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        return $count > 0;
    }

    public static function obtenerRelacionPorId($idUsuarioCasa) {
        global $conn;
        $stmt = $conn->prepare("SELECT idUsuario, idCasa FROM usuario_casa WHERE idUsuarioCasa = ?");
        $stmt->bind_param("i", $idUsuarioCasa);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    //Para db usuario_casa
    public static function obtenerRelaciones() {
        global $conn;
        $sql = "
            SELECT uc.idUsuarioCasa, u.nombre, c.numero AS numeroCasa, uc.tipoRelacion
            FROM usuario_casa uc
            JOIN usuario u ON uc.idUsuario = u.idUsuario
            JOIN casas c ON uc.idCasa = c.idCasa
            ORDER BY c.numero ASC
        ";
        return $conn->query($sql);
    }

    public static function agregarRelacion($idUsuario, $idCasa, $tipoRelacion) {
        global $conn;
        $conn->begin_transaction();
    
        try {
            $stmt = $conn->prepare("
                INSERT INTO usuario_casa (idUsuario, idCasa, tipoRelacion)
                VALUES (?, ?, ?)
            ");
            $stmt->bind_param("iis", $idUsuario, $idCasa, $tipoRelacion);
            $stmt->execute();
    
            $estadoCasa = ($tipoRelacion === 'propietario') ? 'comprada' : 'rentada';
            $stmtCasa = $conn->prepare("UPDATE casas SET estado = ? WHERE idCasa = ?");
            $stmtCasa->bind_param("si", $estadoCasa, $idCasa);
            $stmtCasa->execute();
    
            $conn->commit();
            return true;
        } catch (Exception $e) {
            $conn->rollback();
            return false;
        }
    }

    public static function eliminarRelacion($idUsuarioCasa) {
        global $conn;
        $conn->begin_transaction();

        try {
            $stmtSelect = $conn->prepare("SELECT idUsuario, idCasa FROM usuario_casa WHERE idUsuarioCasa = ?");
            $stmtSelect->bind_param("i", $idUsuarioCasa);
            $stmtSelect->execute();
            $resultado = $stmtSelect->get_result()->fetch_assoc();

            if (!$resultado) {
                throw new Exception("No se encontró la relación con idUsuarioCasa = $idUsuarioCasa");
            }

            $idUsuario = $resultado['idUsuario'];
            $idCasa = $resultado['idCasa'];

            $stmt = $conn->prepare("DELETE FROM usuario_casa WHERE idUsuarioCasa = ?");
            $stmt->bind_param("i", $idUsuarioCasa);
            $stmt->execute();

            $stmtCasa = $conn->prepare("SELECT COUNT(*) AS total FROM usuario_casa WHERE idCasa = ?");
            $stmtCasa->bind_param("i", $idCasa);
            $stmtCasa->execute();
            $totalRelacion = $stmtCasa->get_result()->fetch_assoc()['total'];

            if ($totalRelacion == 0) {
                $stmtUpdateCasa = $conn->prepare("UPDATE casas SET estado = 'disponible' WHERE idCasa = ?");
                $stmtUpdateCasa->bind_param("i", $idCasa);
                $stmtUpdateCasa->execute();
            }

            $conn->commit();
            return true;
        } catch (Exception $e) {
            $conn->rollback();
            error_log("Error al eliminar relación: " . $e->getMessage());
            return false;
        }
    }

    public static function casaYaAsignadaComo($idCasa, $tipoRelacion) {
        global $conn;
        $stmt = $conn->prepare("
            SELECT COUNT(*) AS total FROM usuario_casa 
            WHERE idCasa = ? AND tipoRelacion = ?
        ");
        $stmt->bind_param("is", $idCasa, $tipoRelacion);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        return $res['total'] > 0;
    }

        public static function usuarioTienePropiedades($idUsuario) {
        global $conn;
        $stmt = $conn->prepare("
            SELECT COUNT(*) as total FROM usuario_casa
            WHERE idUsuario = ? AND tipoRelacion = 'propietario'
        ");
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        return $res['total'] > 0;
    }

}
