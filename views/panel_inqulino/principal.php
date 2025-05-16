<?php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'inquilino') {
    header("Location: ../../auth/login.php");
    exit();
}
?>

<?php include '../../includes/header.php'; ?>
<?php include '../../includes/navbar.php'; ?>

<div class="container-fluid px-3 px-md-5 my-5">
  <div class="text-center mb-4">
    <h2 class="fw-bold">Panel de Inquilino</h2>
    <p class="text-muted">Bienvenido, <?= htmlspecialchars($_SESSION['nombre'] ?? 'Inquilino') ?>. Selecciona una opción.</p>
  </div>

  <h4 class="mb-4 text-center">Acciones rápidas</h4>

  <div class="row g-4 justify-content-center">
    <div class="col-sm-12 col-md-4">
      <div class="card h-100 shadow-sm">
        <div class="card-body text-center">
          <h5 class="card-title"><i class="bi bi-credit-card me-2"></i>Pagos</h5>
          <p class="card-text">Realiza y revisa tus pagos de manera segura.</p>
          <a href="realizar_pago.php" class="btn btn-success w-auto">Ir a Pagos</a>
        </div>
      </div>
    </div>

    <div class="col-sm-12 col-md-4">
      <div class="card h-100 shadow-sm">
        <div class="card-body text-center">
          <h5 class="card-title"><i class="bi bi-calendar4-week me-2"></i>Servicios y Reservas</h5>
          <p class="card-text">Solicita mantenimiento o reserva áreas comunes.</p>
          <a href="realizar_solicitud.php" class="btn btn-success w-auto">Gestionar</a>
        </div>
      </div>
    </div>

    <div class="col-sm-12 col-md-4">
      <div class="card h-100 shadow-sm">
        <div class="card-body text-center">
          <h5 class="card-title"><i class="bi bi-people me-2"></i>Foro</h5>
          <p class="card-text">Mira las solicitudes y atención de estas.</p>
          <a href="foro.php" class="btn btn-success w-auto">Ir al Foro</a>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include '../../includes/footer.php'; ?>
