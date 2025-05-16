<?php include '../../controllers/controladorSolicitud.php'; ?>
<?php include '../../includes/header.php'; ?>
<?php include '../../includes/navbar.php'; ?>

<div class="container-fluid px-3 px-md-5 my-5">
  <div class="text-center mb-4">
    <h2 class="fw-bold">Foro de Solicitudes</h2>
    <p class="text-muted">Solicitudes visibles enviadas por los inquilinos</p>
  </div>

  <?php if ($foroSolicitudes && $foroSolicitudes->num_rows > 0): ?>
    <ul class="list-group list-group-flush">
      <?php while ($s = $foroSolicitudes->fetch_assoc()): ?>
        <li class="list-group-item py-4">
          <div class="d-flex justify-content-between flex-wrap">

            <div class="pe-3" style="min-width: 220px;">
              <h5 class="mb-1">
                <i class="bi bi-house-door-fill me-2"></i>Casa <?= htmlspecialchars($s['numeroCasa']) ?>
              </h5>

              <h6 class="text-muted mb-2">
                <i class="bi bi-tag me-1"></i><?= ucfirst(htmlspecialchars($s['tipo'])) ?>
              </h6>

              <span class="badge bg-<?= $s['atendido'] ? 'success' : 'warning text-dark' ?>">
                <?= $s['atendido'] ? 'Atendido' : 'Pendiente' ?>
              </span>
            </div>

            <div class="flex-grow-1">
              <p class="mb-2">
                <i class="bi bi-chat-left-text me-1"></i>
                <?= nl2br(htmlspecialchars($s['descripcion'])) ?>
              </p>

              <p class="mb-0">
                <strong>Comentario del comité:</strong><br>
                <?= $s['comentario'] ? htmlspecialchars($s['comentario']) : '<em class="text-muted">Sin comentarios aún</em>' ?>
              </p>
            </div>

          </div>
        </li>
      <?php endwhile; ?>
    </ul>
  <?php else: ?>
    <div class="alert alert-info text-center">
      No hay solicitudes visibles por el momento.
    </div>
  <?php endif; ?>
</div>

<?php include '../../includes/footer.php'; ?>
