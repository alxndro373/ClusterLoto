<?php
session_start();
include("../db/conexion.php");

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['correo'];
    $contrasenaIngresada = $_POST['contrasena'];

    $stmt = $conn->prepare("SELECT idUsuario, nombre, rol, contrasena FROM usuario WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $usuario = $resultado->fetch_assoc();
        $hashAlmacenado = $usuario['contrasena'];
        
        if (password_verify($contrasenaIngresada, $hashAlmacenado)) {
            $_SESSION['idUsuario'] = $usuario['idUsuario'];
            $_SESSION['nombre'] = $usuario['nombre'];
            $_SESSION['rol'] = $usuario['rol'];

            if ($usuario['rol'] == 'comite') {
                header("Location: ../views/panel_comite/principal.php");
            } elseif ($usuario['rol'] == 'inquilino') {
                header("Location: ../views/panel_inqulino/principal.php");
            } elseif ($usuario['rol'] == 'admin') {
                header("Location: ../index.php");
            } else {
                $error = "Rol no reconocido";
                header("Location: ../index.php");
            }
            exit();
        } else {
            $error = "Contraseña incorrecta";
        }
    } else {
        $error = "Correo o contraseña incorrectos";
    }

    $stmt->close();
    $conn->close();
}
?>

<?php include '../includes/header.php'; ?>
<?php include '../includes/navbar.php'; ?>

<div class="container my-5">
  <div class="card shadow p-4 mx-auto" style="max-width: 400px; background-color: rgba(255, 255, 255, 0.95);">
    <h3 class="text-center mb-4 texto-principal">Iniciar sesión</h3>

    <?php if (!empty($error)): ?>
      <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post" novalidate>
      <div class="mb-3">
        <label for="correo" class="form-label texto-principal">Correo electrónico</label>
        <input type="email" name="correo" id="correo" class="form-control" required>
      </div>
      <div class="mb-3">
        <label for="contrasena" class="form-label texto-principal">Contraseña</label>
        <input type="password" name="contrasena" id="contrasena" class="form-control" required>
      </div>
      <div class="d-grid">
        <button type="submit" class="btn btn-primario">Entrar</button>
      </div>
    </form>
  </div>
</div>

<?php include '../includes/footer.php'; ?>
