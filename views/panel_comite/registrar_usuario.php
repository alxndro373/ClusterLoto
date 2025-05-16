<?php include '../../controllers/controladorUsuario.php'; ?>
<?php include '../../includes/header.php'; ?>
<?php include '../../includes/navbar.php'; ?>

<div class="container my-5 px-3 px-md-5">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <h2 class="fw-bold texto-principal mb-0">Registrar nuevo usuario</h2>
        <a href="usuarios.php" class="btn btn-outline-secundario bg-secundario">
            <i class="bi bi-arrow-left me-1"></i> Volver
        </a>
    </div>

    <?php if (!empty($mensaje)): ?>
        <div class="alert alert-info shadow-sm"><?= htmlspecialchars($mensaje) ?></div>
    <?php endif; ?>

    <form method="POST" class="card shadow-sm bg-secundario p-4 border-0">
        <input type="hidden" name="registrar_usuario" value="1">

        <div class="mb-3">
            <label class="form-label texto-secundario">Nombre completo:</label>
            <input type="text" name="nombre" class="form-control bg-fondo" required>
        </div>

        <div class="mb-3">
            <label class="form-label texto-secundario">Email:</label>
            <input type="email" name="email" class="form-control bg-fondo" required>
        </div>

        <div class="mb-3">
            <label class="form-label texto-secundario">Contraseña:</label>
            <input type="password" name="contrasena" class="form-control bg-fondo" required>
        </div>

        <div class="mb-3">
            <label class="form-label texto-secundario">Rol:</label>
            <select name="rol" id="rol" class="form-select bg-fondo" onchange="toggleCargo()" required>
                <option value="">-- Selecciona --</option>
                <option value="comite">Comité</option>
                <option value="inquilino">Inquilino</option>
            </select>
        </div>

        <div id="cargoDiv" class="mb-3" style="display: none;">
            <label class="form-label texto-secundario">Cargo (solo para comité):</label>
            <select name="cargo" class="form-select bg-fondo">
                <option value="presidente">Presidente</option>
                <option value="secretario">Secretario</option>
                <option value="vocal">Vocal</option>
            </select>
        </div>

        <div class="d-grid mt-4">
            <button type="submit" class="btn btn-primario">
                <i class="bi bi-person-plus me-1"></i> Registrar usuario
            </button>
        </div>
    </form>
</div>

<script>
function toggleCargo() {
    const rol = document.getElementById('rol').value;
    document.getElementById('cargoDiv').style.display = (rol === 'comite') ? 'block' : 'none';
}
</script>

<?php include '../../includes/footer.php'; ?>
