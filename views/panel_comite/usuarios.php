<?php include '../../controllers/controladorUsuario.php'; ?>
<?php include '../../includes/header.php'; ?>
<?php include '../../includes/navbar.php'; ?>

<div class="container my-5 px-3 px-md-5">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <h2 class="fw-bold texto-principal mb-0">Personas que habitan o rentan casas</h2>
        <a href="registrar_usuario.php" class="btn btn-primario">
            <i class="bi bi-person-plus me-1"></i>Registrar nuevo usuario
        </a>
    </div>

    <div id="mensaje-usuario" data-mensaje="<?= htmlspecialchars($mensaje ?? '', ENT_QUOTES) ?>"></div>

    <div class="mb-4">
        <input type="text" id="filtroUsuarios" class="form-control" placeholder="Buscar por nombre o número de casa...">
    </div>

    <?php if ($usuarios->num_rows > 0): ?>
        <div class="row g-4" id="contenedorUsuarios">
            <?php while ($u = $usuarios->fetch_assoc()): ?>
                <div class="col-md-6 col-lg-4 usuario-tarjeta">
                    <div class="card h-100 bg-secundario border-0 shadow-sm transition">
                        <div class="card-body">
                            <h5 class="card-title texto-primario nombre-usuario"><?= htmlspecialchars($u['nombre']) ?></h5>
                            <p class="card-text mb-1 casa-usuario">
                                <i class="bi bi-house-door me-1"></i>
                                <strong>Casa(s):</strong>
                                <?= $u['casas'] ?: '<span class="text-muted">No asignada</span>' ?>
                            </p>
                            <p class="card-text mb-1">
                                <i class="bi bi-person-badge me-1"></i>
                                <strong>Rol:</strong> <?= ucfirst($u['rol']) ?>
                            </p>
                            <?php if ($u['rol'] === 'comite'): ?>
                                <p class="card-text mb-1">
                                    <i class="bi bi-award me-1"></i>
                                    <strong>Cargo:</strong> <?= ucfirst($u['cargo']) ?>
                                </p>
                            <?php endif; ?>
                            <p class="card-text">
                                <i class="bi bi-flag me-1"></i>
                                <strong>Estado(s):</strong>
                                <?= $u['estados'] ?: '<span class="text-muted">N/A</span>' ?>
                            </p>
                        </div>
                        <div class="card-footer bg-white border-0 d-flex justify-content-between">
                            <a href="editar_usuario.php?id=<?= $u['idUsuario'] ?>" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-pencil me-1"></i>Editar
                            </a>
                            <form method="POST" class="d-inline eliminar-form">
                                <input type="hidden" name="eliminar_id" value="<?= $u['idUsuario'] ?>">
                                <button type="submit" class="btn btn-outline-danger btn-sm">
                                    <i class="bi bi-trash me-1"></i>Eliminar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-secondary text-center shadow-sm">
            No hay usuarios registrados aún.
        </div>
    <?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const mensajeDiv = document.getElementById('mensaje-usuario');
    const mensaje = mensajeDiv.getAttribute('data-mensaje');
    if (mensaje) {
        Swal.fire({
            icon: 'info',
            title: 'Mensaje',
            text: mensaje,
            confirmButtonText: 'Aceptar'
        });
    }

    const forms = document.querySelectorAll('.eliminar-form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: '¿Estás seguro?',
                text: "Esta acción no se puede deshacer.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

    const inputFiltro = document.getElementById('filtroUsuarios');
    inputFiltro.addEventListener('input', () => {
        const texto = inputFiltro.value.toLowerCase();
        const tarjetas = document.querySelectorAll('.usuario-tarjeta');

        tarjetas.forEach(tarjeta => {
            const nombre = tarjeta.querySelector('.nombre-usuario')?.textContent.toLowerCase() || '';
            const casa = tarjeta.querySelector('.casa-usuario')?.textContent.toLowerCase() || '';
            const coincide = nombre.includes(texto) || casa.includes(texto);
            tarjeta.style.display = coincide ? 'block' : 'none';
        });
    });
});
</script>

<?php include '../../includes/footer.php'; ?>