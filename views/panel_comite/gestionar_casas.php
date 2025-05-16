<?php include '../../controllers/controladorCasa.php'; ?>
<?php include '../../includes/header.php'; ?>
<?php include '../../includes/navbar.php'; ?>

<div class="container my-5 px-3 px-md-5">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <h2 class="fw-bold texto-principal mb-0">Asignación de Casas a Usuarios</h2>
        <a href="casas.php" class="btn btn-outline-secundario bg-secundario">
            <i class="bi bi-house-door me-1"></i> Registrar nueva casa
        </a>
    </div>

    <?php if (!empty($mensaje)): ?>
        <div class="alert alert-warning shadow-sm"><?= htmlspecialchars($mensaje) ?></div>
    <?php endif; ?>

    <div class="card shadow-sm bg-secundario mb-5 border-0">
        <div class="card-header bg-primario text-white">
            <h5 class="mb-0">Agregar nueva relación</h5>
        </div>
        <div class="card-body">
            <form method="POST">
                <input type="hidden" name="agregar" value="1">

                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label texto-secundario">Usuario</label>
                        <select name="idUsuario" class="form-select bg-fondo" required>
                            <option value="">Selecciona un usuario</option>
                            <?php foreach ($usuarios as $u): ?>
                                <option value="<?= $u['idUsuario'] ?>">
                                    <?= htmlspecialchars($u['nombre']) ?> (<?= htmlspecialchars($u['email']) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label texto-secundario">Casa</label>
                        <select name="idCasa" class="form-select bg-fondo" required>
                            <option value="">Selecciona una casa</option>
                            <?php foreach ($casas as $c): ?>
                                <option value="<?= $c['idCasa'] ?>">Casa #<?= $c['numero'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label texto-secundario">Tipo de relación</label>
                        <select name="tipoRelacion" class="form-select bg-fondo" required>
                            <option value="">Selecciona un tipo</option>
                            <option value="propietario">Propietario</option>
                            <option value="arrendatario">Arrendatario</option>
                        </select>
                    </div>
                </div>

                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-primario">
                        <i class="bi bi-house-door me-1"></i> Asignar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <h4 class="mb-3 texto-principal">Relaciones actuales</h4>

    <?php if ($relaciones->num_rows > 0): ?>
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Usuario</th>
                        <th>Casa</th>
                        <th>Tipo</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($r = $relaciones->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($r['nombre']) ?></td>
                            <td>Casa #<?= htmlspecialchars($r['numeroCasa']) ?></td>
                            <td><?= ucfirst($r['tipoRelacion']) ?></td>
                            <td>
                                <form method="POST" class="d-inline eliminar-relacion-form">
                                    <input type="hidden" name="eliminar" value="1">
                                    <input type="hidden" name="idUsuarioCasa" value="<?= $r['idUsuarioCasa'] ?>">
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash3 me-1"></i> Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="alert alert-secondary">No hay relaciones registradas.</div>
    <?php endif; ?>
</div>

<script>
document.querySelectorAll('.eliminar-relacion-form').forEach(form => {
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        Swal.fire({
            title: '¿Eliminar relación?',
            text: 'Esta acción no se puede deshacer.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then(result => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
});
</script>

<?php include '../../includes/footer.php'; ?>
