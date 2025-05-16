<?php include 'includes/header.php'; ?>
<?php include 'includes/navbar.php'; ?>

<div class="container my-5">
  <div class="text-center mb-5">
    <h1 class="fw-bold" style="color: var(--color-primario);">Sobre Nosotros</h1>
    <p class="texto-subtitulo">Conoce al equipo detrás del sistema de gestión del Fraccionamiento Loto</p>
  </div>

  <div class="row g-4 justify-content-center">
    <?php
      $integrantes = [
        "Kevin Daniel Flores Mayora",
        "Evelyn Monzon Ruiz",
        "Benito de Jesus Carrillo Requena",
        "Miguel Angel Rincon Hernandez",
        "Jose Alexandro Rosas Leon"
      ];
    ?>

    <?php foreach ($integrantes as $nombre): ?>
      <div class="col-md-6 col-lg-4">
        <div class="card bg-secundario h-100 text-center">
          <div class="card-body">
            <h5 class="card-title fw-semibold"><?= htmlspecialchars($nombre) ?></h5>
            <p class="card-text texto-subtitulo">Desarrollador del sistema</p>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>

<?php include 'includes/footer.php'; ?>