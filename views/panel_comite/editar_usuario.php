<?php
include '../../controllers/controladorUsuario.php';
include '../../includes/header.php';
include '../../includes/navbar.php';

if (!ctype_digit($_GET['id'])) {
    header("Location: usuarios.php?error=invalid_id");
    exit();
}

$idUsuario = $_GET['id'];
$usuario = UsuarioModel::obtenerPorId($idUsuario);

if (!$usuario) {
    header("Location: usuarios.php?error=user_not_found");
    exit();
}
?>

<div class="container my-5 px-3 px-md-5">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <h2 class="fw-bold texto-principal mb-0">Editar Usuario</h2>
        <a href="usuarios.php" class="btn btn-outline-secundario bg-secundario">
            <i class="bi bi-arrow-left me-1"></i>Volver
        </a>
    </div>

    <?php if (!empty($mensaje)): ?>
        <div class="alert alert-info shadow-sm"><?= htmlspecialchars($mensaje) ?></div>
    <?php endif; ?>

    <form method="POST" class="card shadow-sm bg-secundario p-4 border-0">
        <input type="hidden" name="editar_usuario" value="1">
        <input type="hidden" name="idUsuario" value="<?= $usuario['idUsuario'] ?>">

        <div class="mb-3">
            <label class="form-label texto-secundario">Nombre completo:</label>
            <input type="text" name="nombre" class="form-control bg-fondo" value="<?= htmlspecialchars($usuario['nombre']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label texto-secundario">Correo electrónico:</label>
            <input type="email" name="email" class="form-control bg-fondo" value="<?= htmlspecialchars($usuario['email']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label texto-secundario">Rol:</label>
            <select name="rol" id="rol" class="form-select bg-fondo" onchange="toggleCargo()" required>
                <option value="inquilino" <?= $usuario['rol'] === 'inquilino' ? 'selected' : '' ?>>Inquilino</option>
                <option value="comite" <?= $usuario['rol'] === 'comite' ? 'selected' : '' ?>>Comité</option>
            </select>
        </div>

        <div id="cargoDiv" class="mb-3" style="display: <?= $usuario['rol'] === 'comite' ? 'block' : 'none' ?>;">
            <label class="form-label texto-secundario">Cargo (solo comité):</label>
            <select name="cargo" id="cargo" class="form-select bg-fondo">
                <option value="">-- Selecciona un cargo --</option>
                <option value="presidente" <?= $usuario['cargo'] === 'presidente' ? 'selected' : '' ?>>Presidente</option>
                <option value="secretario" <?= $usuario['cargo'] === 'secretario' ? 'selected' : '' ?>>Secretario</option>
                <option value="vocal" <?= $usuario['cargo'] === 'vocal' ? 'selected' : '' ?>>Vocal</option>
            </select>
        </div>

        <div class="mt-4 text-end">
            <button type="submit" class="btn btn-primario">
                <i class="bi bi-save me-1"></i>Guardar cambios
            </button>
        </div>
    </form>
</div>

<script>
function toggleCargo() {
    const rol = document.getElementById('rol').value;
    const cargoDiv = document.getElementById('cargoDiv');
    cargoDiv.style.display = (rol === 'comite') ? 'block' : 'none';
}
</script>

<?php include '../../includes/footer.php'; ?>