<?php include '../../controllers/controladorReportes.php'; ?>
<?php include '../../includes/header.php'; ?>
<?php include '../../includes/navbar.php'; ?>

<div class="container my-5">
    <div class="text-center mb-4">
        <h2 class="fw-bold texto-principal">Reportes Financieros</h2>
    </div>

    <form method="POST" class="bg-secundario p-4 rounded shadow-sm">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">

        <div class="mb-3">
            <label for="tipo" class="form-label">Tipo de reporte</label>
            <select name="tipo" id="tipo" class="form-select" onchange="toggleMes(this.value)" required>
                <option value="">-- Seleccionar --</option>
                <option value="mensual" <?= $tipo === 'mensual' ? 'selected' : '' ?>>Mensual</option>
                <option value="anual" <?= $tipo === 'anual' ? 'selected' : '' ?>>Anual</option>
            </select>
        </div>

        <div id="mesDiv" class="mb-3" style="display: <?= $tipo === 'mensual' ? 'block' : 'none' ?>;">
            <label for="mes" class="form-label">Mes</label>
            <select name="mes" id="mes" class="form-select">
                <?php foreach ($meses as $num => $nombre): ?>
                    <option value="<?= $num ?>" <?= $meses == $num ? 'selected' : '' ?>><?= ucfirst($nombre) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="anio" class="form-label">Año</label>
            <input type="number" name="anio" id="anio" class="form-control" value="<?= htmlspecialchars($anio) ?>" required>
        </div>

        <div class="text-start">
            <button type="submit" class="btn btn-primario">Generar reporte</button>
        </div>
    </form>

    <?php if ($reporte): ?>
        <div class="mt-5">
            <h4 class="mb-3 texto-principal">
                Resumen <?= $tipo === 'mensual' ? "mensual de $mes/$anio" : "anual de $anio" ?>
            </h4>
            <ul class="list-group shadow-sm">
                <li class="list-group-item d-flex justify-content-between bg-fondo">
                    <strong>Total ingresos:</strong> <span class="text-success">$<?= number_format($reporte['ingresos'], 2) ?></span>
                </li>
                <li class="list-group-item d-flex justify-content-between bg-fondo">
                    <strong>Total egresos:</strong> <span class="text-danger">$<?= number_format($reporte['egresos'], 2) ?></span>
                </li>
                <li class="list-group-item d-flex justify-content-between bg-fondo">
                    <strong>Saldo:</strong> 
                    <span class="<?= $reporte['saldo'] < 0 ? 'text-danger' : 'text-success' ?>">
                        $<?= number_format($reporte['saldo'], 2) ?>
                    </span>
                </li>
            </ul>
        </div>
    <?php endif; ?>
</div>

<script>
function toggleMes(tipo) {
    document.getElementById('mesDiv').style.display = (tipo === 'mensual') ? 'block' : 'none';
}
</script>

<?php include '../../includes/footer.php'; ?>
