<?php
require_once __DIR__ . '/../db/conexion.php';

class UsuarioModel {
    public static function obtenerUsuarios() {
        global $conn;
        $sql = "
            SELECT 
            u.idUsuario, u.nombre, u.email, u.rol, u.cargo,
            IFNULL(GROUP_CONCAT(DISTINCT c.numero ORDER BY c.numero SEPARATOR ', '), 'No asignada') AS casas,
            IFNULL(GROUP_CONCAT(DISTINCT c.estado ORDER BY c.numero SEPARATOR ', '), 'N/A') AS estados
            FROM usuario u
            LEFT JOIN usuario_casa uc ON uc.idUsuario = u.idUsuario
            LEFT JOIN casas c ON c.idCasa = uc.idCasa
            WHERE u.activo = 1
            GROUP BY u.idUsuario, u.nombre, u.email, u.rol, u.cargo
            ORDER BY u.nombre ASC
        ";
        return $conn->query($sql);
    }

    public static function registrarUsuario($nombre, $email, $contrasena, $rol, $cargo) {
        global $conn;
        $stmt = $conn->prepare("
            INSERT INTO usuario (nombre, email, contrasena, rol, cargo, activo)
            VALUES (?, ?, ?, ?, ?, 1)
        ");
        $stmt->bind_param("sssss", $nombre, $email, $contrasena, $rol, $cargo);
        return $stmt->execute();
    }

    public static function obtenerPorId($idUsuario) {
        global $conn;
        $stmt = $conn->prepare("SELECT * FROM usuario WHERE idUsuario = ? AND activo = 1");
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public static function actualizarUsuario($id, $nombre, $email, $rol, $cargo) {
        global $conn;
        $stmt = $conn->prepare("
            UPDATE usuario SET nombre = ?, email = ?, rol = ?, cargo = ?
            WHERE idUsuario = ?
        ");
        $stmt->bind_param("ssssi", $nombre, $email, $rol, $cargo, $id);
        return $stmt->execute();
    }

    public static function eliminarUsuario($idUsuario) {
        global $conn;
        $stmt = $conn->prepare("UPDATE usuario SET activo = 0 WHERE idUsuario = ?");
        $stmt->bind_param("i", $idUsuario);
        return $stmt->execute();
    }

    public static function obtenerCasasDisponibles() {
        global $conn;
        return $conn->query("SELECT idCasa, numero FROM casas ORDER BY numero ASC");
    }

    public static function correoExiste($email, $idUsuarioActual) {
        global $conn;
        $stmt = $conn->prepare("SELECT idUsuario FROM usuario WHERE email = ? AND idUsuario != ?");
        $stmt->bind_param("si", $email, $idUsuarioActual);
        $stmt->execute();
        $res = $stmt->get_result();
        return $res->num_rows > 0;
    }

    public static function actualizarCargoUsr($idUsuario, $nuevoCargo) {
        global $conn;
        $stmt = $conn->prepare("UPDATE usuario SET cargo = ? WHERE idUsuario = ?");
        $stmt->bind_param("si", $nuevoCargo, $idUsuario);
        return $stmt->execute();
    }

    public static function obtenerCasasPorUsuario($idUsuario) {
        global $conn;
        $stmt = $conn->prepare("
            SELECT c.idCasa, c.numero, uc.tipoRelacion
            FROM usuario_casa uc
            JOIN casas c ON uc.idCasa = c.idCasa
            WHERE uc.idUsuario = ?
        ");
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();
        return $stmt->get_result();
    }
}
