<?php
require_once __DIR__ . '/../models/modeloCasa.php';
require_once __DIR__ . '/../models/modeloUsuario.php';

session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'comite') {
    header("Location: /auth/login.php");
    exit();
}

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['agregar_casa'])) {
    $numero = $_POST['numero'];

    if (CasaModel::numeroExiste($numero)) {
        $mensaje = "Ya existe una casa con ese numero.";
    } else {
        if (CasaModel::agregar($numero)) {
            $mensaje = "Casa registrada correctamente.";
        } else {
            $mensaje = "Error al registrar la casa.";
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['eliminar_casa'])) {
    $id = $_POST['idCasa'];

    if (CasaModel::estaAsignada($id)) {
        $mensaje = "No puedes eliminar esta casa porque está asignada a un usuario.";
    } else {
        if (CasaModel::eliminar($id)) {
            $mensaje = "Casa eliminada correctamente.";
        } else {
            $mensaje = "Error al eliminar la casa.";
        }
    }
}

//Para db usuario_casa
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['agregar'])) {
        $idUsuario = $_POST['idUsuario'];
        $idCasa = $_POST['idCasa'];
        $tipoRelacion = $_POST['tipoRelacion'];

        if ($tipoRelacion === 'arrendatario' && !CasaModel::usuarioTienePropiedades($idCasa)) {
            $mensaje = "No puedes asignar un arrendatario a una casa sin propietario.";
        } elseif (CasaModel::casaYaAsignadaComo($idCasa, $tipoRelacion)) {
            $mensaje = "Esta casa ya tiene un $tipoRelacion asignado.";
        } else {
            if (CasaModel::agregarRelacion($idUsuario, $idCasa, $tipoRelacion)) {
                $mensaje = "Relación registrada correctamente.";
            } else {
                $mensaje = "Error al registrar la relación.";
            }
        }
    }

    if (isset($_POST['eliminar'])) {
        $idRelacion = $_POST['idUsuarioCasa'];
        if (CasaModel::eliminarRelacion($idRelacion)) {
            $mensaje = "Relación eliminada.";
        }
    }
}

$relaciones = CasaModel::obtenerRelaciones();
$usuarios = UsuarioModel::obtenerUsuarios();
$casas = CasaModel::obtenerTodas();
?>