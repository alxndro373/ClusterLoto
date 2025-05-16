<?php include '../../controllers/controladorVerificarPagos.php'; ?>
<?php include '../../includes/header.php'; ?>
<?php include '../../includes/navbar.php'; ?>

<div class="container my-5 px-3 px-md-5">
    <div class="text-center mb-4">
        <h2 class="fw-bold texto-principal">Verificación de Pagos</h2>
    </div>

    <?php if (!empty($mensaje)): ?>
        <div class="alert alert-info text-center shadow-sm">
            <?= htmlspecialchars($mensaje) ?>
        </div>
    <?php endif; ?>

    <?php if ($pagosPendientes->num_rows > 0): ?>
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle shadow-sm text-center">
                <thead class="bg-primario texto-invertido">
                    <tr>
                        <th>ID</th>
                        <th><i class="bi bi-calendar-date me-1"></i>Fecha</th>
                        <th><i class="bi bi-house-door me-1"></i>Casa</th>
                        <th><i class="bi bi-person-circle me-1"></i>Inquilino</th>
                        <th><i class="bi bi-cash-coin me-1"></i>Monto</th>
                        <th><i class="bi bi-exclamation-triangle me-1"></i>Recargo</th>
                        <th><i class="bi bi-clipboard-check me-1"></i>Concepto</th>
                        <th><i class="bi bi-tools me-1"></i>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $pagosPendientes->fetch_assoc()): ?>
                        <tr class="bg-fondo">
                            <td><?= $row['idPago'] ?></td>
                            <td><?= date("d-m-Y", strtotime($row['fechaPago'])) ?></td>
                            <td><?= htmlspecialchars($row['numeroCasa']) ?></td>
                            <td><?= htmlspecialchars($row['nombre']) ?></td>
                            <td>$<?= number_format($row['monto'], 2) ?></td>
                            <td>$<?= number_format($row['recargo'], 2) ?></td>
                            <td><?= htmlspecialchars($row['concepto']) ?></td>
                            <td>
                                <form method="POST" class="d-inline">
                                    <input type="hidden" name="idPago" value="<?= $row['idPago'] ?>">
                                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                    <input type="hidden" name="accion" value="verificar">
                                    <button type="submit" class="btn btn-primario btn-sm mb-1">
                                        <i class="bi bi-check-circle me-1"></i>Verificar
                                    </button>
                                </form>
                                <form method="POST" class="d-inline">
                                    <input type="hidden" name="idPago" value="<?= $row['idPago'] ?>">
                                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                    <input type="hidden" name="accion" value="rechazar">
                                    <button type="submit" class="btn btn-outline-primary btn-sm mb-1">
                                        <i class="bi bi-x-circle me-1"></i>Rechazar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="alert alert-secondary text-center shadow-sm">
            No hay pagos pendientes de verificación.
        </div>
    <?php endif; ?>
</div>

<?php include '../../includes/footer.php'; ?>
