<?php include '../../controllers/controladorPagos.php'; ?>
<?php include '../../includes/header.php'; ?>
<?php include '../../includes/navbar.php'; ?>

<div class="container-fluid px-3 px-md-5 my-5">
  <div class="mb-4 text-center">
    <h2 class="fw-bold">Historial de Pagos</h2>
    <a href="realizar_pago.php" class="btn btn-outline-secondary mt-2">
      <i class="bi bi-arrow-left me-1"></i> Volver a registrar pago
    </a>
  </div>

  <?php if ($historialPagos->num_rows > 0): ?>
    <div class="table-responsive">
      <table class="table table-bordered table-hover text-center align-middle shadow-sm">
        <thead class="table-dark">
          <tr>
            <th>Fecha</th>
            <th>Concepto</th>
            <th>Monto</th>
            <th>Recargo</th>
            <th>Total</th>
            <th>Estado</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($pago = $historialPagos->fetch_assoc()): ?>
            <tr>
              <td><?= date("d-m-Y", strtotime($pago['fechaPago'])) ?></td>
              <td><?= htmlspecialchars($pago['concepto']) ?></td>
              <td>$<?= number_format($pago['monto'], 2) ?></td>
              <td>$<?= number_format($pago['recargo'], 2) ?></td>
              <td class="fw-bold">$<?= number_format($pago['monto'] + $pago['recargo'], 2) ?></td>
              <td>
                <?php if ($pago['verificado']): ?>
                  <span class="badge bg-success">Verificado</span>
                <?php else: ?>
                  <span class="badge bg-warning text-dark">En revisión</span>
                <?php endif; ?>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  <?php else: ?>
    <div class="alert alert-info text-center">
      No has registrado ningún pago aún.
    </div>
  <?php endif; ?>
</div>

<?php include '../../includes/footer.php'; ?>
