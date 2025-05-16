<?php
include '../../controllers/controladorEstadoCuenta.php';

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); //Genera un token único de 32 bytes
}
?>

<?php include '../../includes/header.php'; ?>
<?php include '../../includes/navbar.php'; ?>

<div class="container py-4 texto-principal">
  <div class="text-center mb-4">
    <h2 class="fw-bold">Estado de Cuenta</h2>
  </div>

  <div class="card shadow-sm mb-5 bg-secundario">
    <div class="card-body">
      <form method="POST" class="row g-3 align-items-end">

        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

        <div class="col-md-3">
          <label for="mes" class="form-label">Mes:</label>
          <select name="mes" id="mes" class="form-select bg-fondo" required>
            <?php foreach ($meses as $numero => $nombre): ?>
              <option value="<?= $numero ?>" <?= ($numero == $mesActual) ? 'selected' : '' ?>>
                <?= $nombre ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="col-md-2">
          <label for="anio" class="form-label">Año:</label>
          <input type="number" name="anio" id="anio" min="2020" max="<?= date("Y") ?>" value="<?= $anioActual ?>" class="form-control bg-fondo" required>
        </div>

        <div class="col-md-4">
          <label for="idUsuario" class="form-label">Inquilino:</label>
          <select name="idUsuario" id="idUsuario" class="form-select bg-fondo">
            <option value="">-- Todos --</option>
            <?php
            $usuarios = UsuarioModel::obtenerUsuarios();
            while ($u = $usuarios->fetch_assoc()):
            ?>
              <option value="<?= $u['idUsuario'] ?>"><?= htmlspecialchars($u['nombre']) ?> (<?= htmlspecialchars($u['email']) ?>)</option>
            <?php endwhile; ?>
          </select>
        </div>

        <div class="col-md-3">
          <button type="submit" class="btn btn-primario w-100">Consultar</button>
        </div>
      </form>
    </div>
  </div>

  <?php if (!empty($estadoCuenta)): ?>

    <div class="mb-4">
      <h4 class="mb-3">Ingresos</h4>
      <?php if (!empty($estadoCuenta['pagos'])): ?>
        <div class="table-responsive">
          <table class="table table-bordered align-middle table-striped bg-white">
            <thead class="table-light">
              <tr>
                <th>Fecha</th>
                <th>Inquilino</th>
                <th>Casa(s)</th>
                <th>Concepto</th>
                <th>Monto</th>
                <th>Recargo</th>
                <th>Total</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($estadoCuenta['pagos'] as $p): ?>
                <tr>
                  <td><?= date("d-m-Y", strtotime($p['fechaPago'])) ?></td>
                  <td><?= htmlspecialchars($p['nombre']) ?></td>
                  <td><?= $p['numeroCasa'] ?></td>
                  <td><?= htmlspecialchars($p['concepto']) ?></td>
                  <td>$<?= number_format($p['monto'], 2) ?></td>
                  <td>$<?= number_format($p['recargo'], 2) ?></td>
                  <td>$<?= number_format($p['monto'] + $p['recargo'], 2) ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php else: ?>
        <div class="alert alert-warning">No hay ingresos registrados.</div>
      <?php endif; ?>
    </div>

    <div class="mb-4">
      <h4 class="mb-3">Egresos</h4>
      <?php if (!empty($estadoCuenta['egresos'])): ?>
        <div class="table-responsive">
          <table class="table table-bordered align-middle table-striped bg-white">
            <thead class="table-light">
              <tr>
                <th>Fecha</th>
                <th>Motivo</th>
                <th>Monto</th>
                <th>Pagado a</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($estadoCuenta['egresos'] as $e): ?>
                <tr>
                  <td><?= date("d-m-Y", strtotime($e['fecha'])) ?></td>
                  <td><?= htmlspecialchars($e['motivo']) ?></td>
                  <td>$<?= number_format($e['monto'], 2) ?></td>
                  <td><?= htmlspecialchars($e['pagado_a']) ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php else: ?>
        <div class="alert alert-warning">No hay egresos registrados.</div>
      <?php endif; ?>
    </div>

    <div class="card shadow-sm bg-secundario">
      <div class="card-body">
        <h4 class="mb-3">Resumen</h4>
        <p><strong>Total Ingresos:</strong> $<?= number_format($totalIngresos, 2) ?></p>
        <p><strong>Total Egresos:</strong> $<?= number_format($totalEgresos, 2) ?></p>
        <p><strong>Saldo:</strong>
          <span class="fw-bold <?= ($totalIngresos - $totalEgresos) < 0 ? 'text-danger' : 'text-success' ?>">
            $<?= number_format($totalIngresos - $totalEgresos, 2) ?>
          </span>
        </p>
      </div>
    </div>

  <?php endif; ?>
</div>

<?php include '../../includes/footer.php'; ?>