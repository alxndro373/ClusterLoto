<?php
require_once __DIR__ . '/../models/modeloUsuario.php';
require_once __DIR__ . '/../models/modeloCasa.php';

session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'comite') {
    header("Location: /auth/login.php");
    exit();
}

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['registrar_usuario'])) {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $contrasena = password_hash($_POST['contrasena'], PASSWORD_DEFAULT);
    $rol = $_POST['rol'];
    $cargo = ($rol === 'comite') ? $_POST['cargo'] : null;
    $idCasa = !empty($_POST['idCasa']) ? $_POST['idCasa'] : null;
    $tipoRelacion = $_POST['tipoRelacion'] ?? null;

    if (UsuarioModel::correoExiste($email, 0)) {
        $mensaje = "El correo ya está registrado.";
    } else {
        if (UsuarioModel::registrarUsuario($nombre, $email, $contrasena, $rol, $cargo)) {
            $nuevoUsuarioId = $GLOBALS['conn']->insert_id;

            if ($idCasa && $tipoRelacion) {
                CasaModel::agregarRelacion($nuevoUsuarioId, $idCasa, $tipoRelacion);
            }

            $mensaje = "Usuario registrado correctamente.";
        } else {
            $mensaje = "Error al registrar usuario.";
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['eliminar_id'])) {
    $idEliminar = $_POST['eliminar_id'];
    if (UsuarioModel::eliminarUsuario($idEliminar)) {
        $mensaje = "Usuario eliminado correctamente.";
    } else {
        $mensaje = "Error al eliminar el usuario.";
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['editar_usuario'])) {
    $idUsuario = $_POST['idUsuario'];
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $rol = $_POST['rol'];
    $cargo = ($rol === 'comite') ? trim($_POST['cargo']) : null;

    if (UsuarioModel::correoExiste($email, $idUsuario)) {
        $mensaje = "El correo ya está registrado con otro usuario.";
    } elseif ($rol === 'comite' && empty($cargo)) {
        $mensaje = "Debe seleccionar un cargo para el comité.";
    } else {
        if (UsuarioModel::actualizarUsuario($idUsuario, $nombre, $email, $rol, $cargo)) {
            header("Location: /views/panel_comite/usuarios.php?actualizado=1");
            exit();
        } else {
            $mensaje = "Error al actualizar el usuario.";
        }
    }
}

$casasDisponibles = UsuarioModel::obtenerCasasDisponibles();
$usuarios = UsuarioModel::obtenerUsuarios();
