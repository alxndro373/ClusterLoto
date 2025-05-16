<?php include '../../controllers/controladorEgresos.php'; ?>
<?php include '../../includes/header.php'; ?>
<?php include '../../includes/navbar.php'; ?>

<div class="container-fluid px-3 px-md-5 my-5">
  <div class="text-center mb-4">
    <h2 class="fw-bold texto-principal">Historial de Egresos</h2>
    <a href="egresos.php" class="btn btn-primario mt-2">
      <i class="bi bi-arrow-left me-1"></i> Volver al registro de egresos
    </a>
  </div>

  <?php if ($egresos->num_rows > 0): ?>
    <div class="table-responsive shadow-sm">
      <table class="table table-bordered table-hover align-middle text-center">
        <thead style="background-color: var(--color-primario); color: var(--color-invertido);">
          <tr>
            <th>Fecha</th>
            <th>Monto</th>
            <th>Pagado a</th>
            <th>Motivo</th>
            <th>Registrado por</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = $egresos->fetch_assoc()): ?>
            <tr style="background-color: var(--color-neutro); color: var(--color-texto);">
              <td><?= date("d-m-Y", strtotime($row['fecha'])) ?></td>
              <td>$<?= number_format($row['monto'], 2) ?></td>
              <td><?= htmlspecialchars($row['pagado_a']) ?></td>
              <td><?= htmlspecialchars($row['motivo']) ?></td>
              <td><?= htmlspecialchars($row['nombre']) ?> (<?= ucfirst($row['cargo']) ?>)</td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  <?php else: ?>
    <div class="alert alert-info text-center">
      No hay egresos registrados aún.
    </div>
  <?php endif; ?>
</div>


<?php include '../../includes/footer.php'; ?>
