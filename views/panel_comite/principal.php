<?php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'comite') {
    header("Location: ../../auth/login.php");
    exit();
}
?>

<?php include '../../includes/header.php'; ?>
<?php include '../../includes/navbar.php'; ?>

<div class="container py-4 texto-principal">
  <div class="text-center mb-5">
    <h2 class="fw-bold">Panel del Comité</h2>
    <p class="text-muted">Bienvenido, <?= htmlspecialchars($_SESSION['nombre'] ?? 'Comité') ?>. Selecciona una acción.</p>
  </div>

  <h4 class="mb-4 text-center">Acciones rápidas</h4>

  <div class="row g-4 justify-content-center">

    <?php
    //Opciones
    $opciones = [
      [
        "titulo" => "Estados de Cuenta",
        "icono" => "bi-file-earmark-text",
        "texto" => "Consulta el estado financiero de las casas.",
        "link" => "estado_cuenta.php",
        "boton" => "Ver Estados"
      ],
      [
        "titulo" => "Registrar Egresos",
        "icono" => "bi-cash-coin",
        "texto" => "Añade pagos realizados por el comité.",
        "link" => "egresos.php",
        "boton" => "Registrar"
      ],
      [
        "titulo" => "Reportes",
        "icono" => "bi-graph-up-arrow",
        "texto" => "Genera y visualiza reportes financieros y operativos.",
        "link" => "reportes.php",
        "boton" => "Ver Reportes"
      ],
      [
        "titulo" => "Verificar Pagos",
        "icono" => "bi-shield-check",
        "texto" => "Revisa los pagos realizados y envía el acuse correspondiente.",
        "link" => "verificar_pagos.php",
        "boton" => "Verificar"
      ],
      [
        "titulo" => "Gestionar Solicitudes",
        "icono" => "bi-tools",
        "texto" => "Aprueba o responde solicitudes de los inquilinos.",
        "link" => "gestionar_solicitudes.php",
        "boton" => "Gestionar"
      ],
      [
        "titulo" => "Usuarios",
        "icono" => "bi-people-fill",
        "texto" => "Agrega, edita o elimina usuarios del sistema.",
        "link" => "usuarios.php",
        "boton" => "Administrar"
      ],
      [
        "titulo" => "Casas",
        "icono" => "bi-house-fill",
        "texto" => "Gestiona las casas asignadas y su información.",
        "link" => "gestionar_casas.php",
        "boton" => "Gestionar Casas"
      ]
    ];

    foreach ($opciones as $opcion): ?>
      <div class="col-md-4">
        <div class="card h-100 shadow-sm border-0 bg-secundario">
          <div class="card-body text-center">
            <h5 class="card-title"><i class="bi <?= $opcion['icono'] ?> me-2"></i><?= $opcion['titulo'] ?></h5>
            <p class="card-text"><?= $opcion['texto'] ?></p>
            <a href="<?= $opcion['link'] ?>" class="btn btn-primario w-auto"><?= $opcion['boton'] ?></a>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>

<?php include '../../includes/footer.php'; ?>
