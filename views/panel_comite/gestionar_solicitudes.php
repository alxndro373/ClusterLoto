<?php include '../../controllers/controladorGestorSoli.php'; ?>
<?php include '../../includes/header.php'; ?>
<?php include '../../includes/navbar.php'; ?>

<div class="container my-5 px-3 px-md-5">
    <div class="text-center mb-4">
        <h2 class="fw-bold texto-principal">Gestión de Solicitudes</h2>
    </div>

    <?php if (!empty($mensaje)): ?>
        <div class="alert alert-info text-center shadow-sm">
            <?= htmlspecialchars($mensaje) ?>
        </div>
    <?php endif; ?>

    <?php if ($solicitudes->num_rows > 0): ?>
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle shadow-sm text-center">
                <thead class="bg-primario texto-invertido">
                    <tr>
                        <th><i class="bi bi-calendar-date me-1"></i>Fecha</th>
                        <th><i class="bi bi-house me-1"></i>Casa</th>
                        <th><i class="bi bi-tags me-1"></i>Tipo</th>
                        <th><i class="bi bi-file-earmark-text me-1"></i>Descripción</th>
                        <th><i class="bi bi-info-circle me-1"></i>Estado</th>
                        <th><i class="bi bi-chat-left-dots me-1"></i>Comentario</th>
                        <th><i class="bi bi-tools me-1"></i>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($s = $solicitudes->fetch_assoc()): ?>
                        <tr class="bg-fondo">
                            <td><?= date("d-m-Y", strtotime($s['fechaSolicitud'])) ?></td>
                            <td><?= htmlspecialchars($s['numeroCasa']) ?></td>
                            <td><?= ucfirst(htmlspecialchars($s['tipo'])) ?></td>
                            <td><?= htmlspecialchars($s['descripcion']) ?></td>
                            <td>
                                <span class="badge <?= $s['atendido'] ? 'bg-success' : 'bg-warning text-dark' ?>">
                                    <?= $s['atendido'] ? 'Atendido' : 'Pendiente' ?>
                                </span>
                            </td>
                            <td><?= $s['comentario'] ? htmlspecialchars($s['comentario']) : '—' ?></td>
                            <td>
                                <?php if (!$s['atendido']): ?>
                                    <form method="POST">
                                        <input type="hidden" name="idSolicitud" value="<?= $s['idSolicitud'] ?>">
                                        <div class="mb-2">
                                            <textarea name="comentario" class="form-control form-control-sm" rows="2" placeholder="Comentario" required></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-primario btn-sm w-100">
                                            <i class="bi bi-check2-circle me-1"></i>Marcar como atendida
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <span class="text-muted">—</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="alert alert-secondary text-center shadow-sm">
            No hay solicitudes registradas aún.
        </div>
    <?php endif; ?>
</div>

<?php include '../../includes/footer.php'; ?>