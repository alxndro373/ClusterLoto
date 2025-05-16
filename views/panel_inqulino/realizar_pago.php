<?php include '../../controllers/controladorPagos.php'; ?>
<?php include '../../includes/header.php'; ?>
<?php include '../../includes/navbar.php'; ?>

<div class="container-fluid px-3 px-md-5 my-5">
  <div class="text-center mb-4">
    <h2 class="fw-bold">Pago Mensual de Mantenimiento</h2>
    <a href="historial_pagos.php" class="btn btn-outline-primary mt-2">
      <i class="bi bi-clock-history me-1"></i> Ver Historial de Pagos
    </a>
  </div>

  <?php if (!empty($mensaje)) : ?>
    <div class="alert alert-info text-center"><?= htmlspecialchars($mensaje) ?></div>
  <?php endif; ?>

  <?php if ($sinCasas): ?>
    <div class="alert alert-danger text-center">
      <strong>Atención:</strong> No tienes casas asignadas. Contacta al comité para poder realizar pagos.
    </div>
  <?php else: ?>
    <?php
      $diaActual = date("d");
      $recargo = ($diaActual > 28) ? 50 : 0;
      $casasSinPago = [];

      while ($casa = $casas->fetch_assoc()) {
          if (!PagoModel::casaYaPagadaEsteMes($casa['idCasa'])) {
              $casasSinPago[] = $casa;
          }
      }
    ?>

    <?php if (empty($casasSinPago)): ?>
      <div class="alert alert-success text-center">
        Ya se ha registrado un pago este mes para todas tus casas. ¡Gracias por mantenerte al día!
      </div>
    <?php else: ?>

      <?php if ($diaActual >= 21): ?>
        <div class="alert alert-warning text-center">
          <strong>¡Atención!</strong> Aún no has realizado el pago de todas tus casas.<br>
          A partir del día <strong>28</strong> se aplicará un <strong>recargo de $50</strong>.
        </div>
      <?php endif; ?>

      <div class="row g-4">
        <?php foreach ($casasSinPago as $casa): ?>
          <div class="col-md-6 col-lg-4">
            <div class="card shadow-sm h-100">
              <div class="card-body d-flex flex-column justify-content-between">
                <form method="POST">
                  <input type="hidden" name="idCasa" value="<?= $casa['idCasa'] ?>">
                  <input type="hidden" name="numeroCasa" value="<?= htmlspecialchars($casa['numero']) ?>">

                  <h5 class="card-title">Casa #<?= htmlspecialchars($casa['numero']) ?></h5>
                  <p class="card-text mb-1"><i class="bi bi-calendar-date me-1"></i><strong> Hoy es:</strong> <?= date("d-m-Y") ?></p>
                  <p class="card-text mb-1"><i class="bi bi-cash-coin me-1"></i>Cuota fija: $650</p>
                  <p class="card-text mb-1"><i class="bi bi-exclamation-circle me-1"></i>Recargo actual: $<?= $recargo ?></p>
                  <p class="card-text fw-bold">Total a pagar: $<?= 650 + $recargo ?></p>

                  <button type="submit" class="btn btn-success w-100 mt-3">
                    <i class="bi bi-credit-card-2-front me-1"></i>Pagar esta casa
                  </button>
                </form>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  <?php endif; ?>
</div>

<?php include '../../includes/footer.php'; ?>
