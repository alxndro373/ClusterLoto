<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<nav class="navbar navbar-expand-lg navbar-custom shadow-sm">
  <div class="container-fluid">
    <a class="navbar-brand d-flex align-items-center" href="/index.php">
      <img src="/assets/logo_sinfondo.png" alt="logo_loto" height="40" class="me-2">
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContenido" aria-controls="navbarContenido" aria-expanded="false" aria-label="Menú de navegación">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarContenido">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link" href="/index.php">Inicio</a>
        </li>

        <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'comite'): ?>
          <li class="nav-item">
            <a class="nav-link" href="/views/panel_comite/principal.php">Panel Comité</a>
          </li>
        <?php elseif (isset($_SESSION['rol']) && $_SESSION['rol'] === 'inquilino'): ?>
          <li class="nav-item">
            <a class="nav-link" href="/views/panel_inqulino/principal.php">Panel Inquilino</a>
          </li>
        <?php endif; ?>
      </ul>

      <ul class="navbar-nav ms-auto mb-2 mb-lg-0 d-flex align-items-center">
        <?php if (isset($_SESSION['rol'])): ?>
          <li class="nav-item">
            <span class="navbar-text text-white me-lg-3 d-block d-lg-inline">
              Bienvenido <?= htmlspecialchars($_SESSION['nombre'] ?? 'Usuario') ?>
            </span>
          </li>
          <li class="nav-item">
            <a class="nav-link text-white" href="/auth/logout.php">Cerrar sesión</a>
          </li>
        <?php else: ?>
          <li class="nav-item">
            <a class="nav-link text-white" href="/auth/login.php">Iniciar Sesión</a>
          </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>