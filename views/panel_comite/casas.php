<?php include '../../controllers/controladorCasa.php'; ?>
<?php include '../../includes/header.php'; ?>
<?php include '../../includes/navbar.php'; ?>

<div class="container my-5 px-3 px-md-5">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <h2 class="fw-bold texto-principal mb-0">Panel de Casas</h2>
        <a href="gestionar_casas.php" class="btn btn-outline-secundario bg-secundario">
            <i class="bi bi-arrow-left me-1"></i> Volver
        </a>
    </div>

    <?php if (!empty($mensaje)): ?>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Registro exitoso',
                text: <?= json_encode($mensaje) ?>,
                confirmButtonColor: '#0d6efd'
            });
        </script>
    <?php endif; ?>

    <div class="card shadow-sm bg-secundario border-0">
        <div class="card-header bg-primario text-white">
            <h5 class="mb-0">Agregar nueva casa</h5>
        </div>
        <div class="card-body">
            <form method="POST" class="row g-3">
                <input type="hidden" name="agregar_casa" value="1">

                <div class="col-md-6">
                    <label for="numeroCasa" class="form-label texto-secundario">Número de casa</label>
                    <input type="number" name="numero" id="numeroCasa" class="form-control bg-fondo" required>
                </div>

                <div class="col-12 text-start mt-3">
                    <button type="submit" class="btn btn-primario">
                        <i class="bi bi-house-door me-1"></i> Agregar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>