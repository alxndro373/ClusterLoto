<?php include '../../controllers/controladorSolicitud.php'; ?>
<?php include '../../includes/header.php'; ?>
<?php include '../../includes/navbar.php'; ?>

<div class="container-fluid px-3 px-md-5 my-5">
  <div class="text-center mb-4">
    <h2 class="fw-bold">Nueva Solicitud</h2>
  </div>

  <?php if (!empty($mensaje)) : ?>
    <div class="alert alert-info text-center"><?= htmlspecialchars($mensaje) ?></div>
  <?php endif; ?>

  <div class="row justify-content-center">
    <div class="col-lg-6">
      <form method="POST" class="card shadow-sm p-4">
        <div class="mb-3">
          <label for="tipo" class="form-label">Tipo de solicitud:</label>
          <select name="tipo" id="tipo" class="form-select" onchange="mostrarCampos()" required>
            <option value="">-- Seleccionar --</option>
            <option value="reserva">Reserva (palapa o alberca)</option>
            <option value="servicio">Servicio (mantenimiento)</option>
          </select>
        </div>

        <div id="reservaCampos" style="display:none;">
          <div class="mb-3">
            <label for="espacio" class="form-label">Espacio a reservar:</label>
            <select name="espacio" id="espacio" class="form-select">
              <option value="palapa">Palapa</option>
              <option value="alberca">Alberca</option>
            </select>
          </div>

          <div class="mb-3">
            <label for="fecha_reserva" class="form-label">Fecha de reserva:</label>
            <input type="date" name="fecha_reserva" id="fecha_reserva" class="form-control">
          </div>
        </div>

        <div id="servicioCampos" style="display:none;">
          <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción del servicio:</label>
            <textarea name="descripcion" id="descripcion" rows="4" class="form-control" placeholder="Ej: Fuga de agua en la cocina"></textarea>
          </div>
        </div>

        <div class="d-grid">
          <button type="submit" class="btn btn-success">
            <i class="bi bi-send me-1"></i>Enviar solicitud
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
function mostrarCampos() {
  let tipo = document.getElementById('tipo').value;
  document.getElementById('reservaCampos').style.display = (tipo === 'reserva') ? 'block' : 'none';
  document.getElementById('servicioCampos').style.display = (tipo === 'servicio') ? 'block' : 'none';

  if (tipo === 'reserva') {
    const hoy = new Date().toISOString().split('T')[0];
    document.getElementById('fecha_reserva').setAttribute('min', hoy);
  }
}
</script>

<?php include '../../includes/footer.php'; ?>
