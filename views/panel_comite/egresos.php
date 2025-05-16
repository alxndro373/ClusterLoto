<?php include '../../controllers/controladorEgresos.php'; ?>
<?php include '../../includes/header.php'; ?>
<?php include '../../includes/navbar.php'; ?>

<div class="container my-5">
  <div class="text-center mb-4">
    <h2 class="fw-bold">Registrar nuevo egreso</h2>
    <a href="historial_egresos.php" class="btn btn-outline-primary mt-2">Ir al historial de egresos</a>
  </div>

  <?php if (!empty($mensaje)): ?>
    <script>
      Swal.fire({
        icon: 'success',
        title: '¡Éxito!',
        text: <?= json_encode($mensaje) ?>,
        confirmButtonColor: '#412855'
      });
    </script>
  <?php endif; ?>

  <?php if (!empty($mensaje_error)): ?>
    <script>
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: <?= json_encode($mensaje_error) ?>,
        confirmButtonColor: '#412855'
      });
    </script>
  <?php endif; ?>

  <div class="card shadow-sm mx-auto" style="max-width: 600px;">
    <div class="card-body">
      <form method="POST" class="row g-3">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

        <div class="col-12">
          <label for="fecha" class="form-label">Fecha:</label>
          <input type="date" name="fecha" id="fecha" class="form-control" required>
        </div>

        <div class="col-12">
          <label for="monto" class="form-label">Monto:</label>
          <input type="number" name="monto" id="monto" class="form-control" step="0.01" required>
        </div>

        <div class="col-12">
          <label for="pagado_a" class="form-label">Pagado a:</label>
          <input type="text" name="pagado_a" id="pagado_a" class="form-control" required>
        </div>

        <div class="col-12">
          <label for="motivo" class="form-label">Motivo:</label>
          <textarea name="motivo" id="motivo" rows="4" class="form-control" required></textarea>
        </div>

        <div class="col-12 text-center">
          <button type="submit" class="btn btn-primario px-4">Registrar egreso</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php include '../../includes/footer.php'; ?>
