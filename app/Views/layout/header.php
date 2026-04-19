<?php require_once dirname(__DIR__, 3) . '/core/bootstrap.php'; ?>
<!doctype html>
<html lang="es">

<head>
  <title>Aplicación Web</title>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <link rel="icon" type="image/x-icon" href="<?= $public_base; ?>img/deadpool.ico">
  <!-- Bootstrap CSS v5.2.1 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">

  <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>

  <!-- DataTables con Bootstrap 5 + Responsive -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" />
  <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap5.min.css" />
  
  <!-- Font Awesome 6 -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

  <!-- Estilos Personalizados -->
  <link rel="stylesheet" href="<?= $public_base; ?>css/style.css">

  <script src="http://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
  <header>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
      <div class="container">
        <a class="navbar-brand fw-bold" href="<?= $public_base; ?>">
          <i class="fas fa-laptop-code me-2"></i>Sistema Web
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNavbar">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
              <a class="nav-link" href="<?= $public_base; ?>empleados">
                <i class="fas fa-user-tie me-1"></i> Empleados
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="<?= $public_base; ?>puestos">
                <i class="fas fa-briefcase me-1"></i> Puestos
              </a>
            </li>
            <?php if ($nombreUsuario == "Administrador") : ?>
              <li class="nav-item">
                <a class="nav-link" href="<?= $public_base; ?>usuarios">
                  <i class="fas fa-users-cog me-1"></i> Usuarios
                </a>
              </li>
            <?php endif; ?>
          </ul>
          <div class="d-flex align-items-center">
            <span class="navbar-text me-3 d-none d-lg-inline">
              <i class="fas fa-user-circle me-1"></i> <?= htmlspecialchars($nombreUsuario); ?>
            </span>
            <form action="<?= htmlspecialchars($public_base, ENT_QUOTES, 'UTF-8'); ?>cerrar" method="post" class="d-inline">
              <input type="hidden" name="csrf_token" value="<?= htmlspecialchars((string)$csrfToken, ENT_QUOTES, 'UTF-8'); ?>">
              <button type="submit" class="btn btn-outline-light btn-sm">
                <i class="fas fa-sign-out-alt me-1"></i> Cerrar Sesión
              </button>
            </form>
          </div>
        </div>
      </div>
    </nav>
  </header>

  <main class="container">
    <?php if ($flash !== null) : ?>
      <div id="flash-data" 
           data-icon="<?= htmlspecialchars($flash['icon']); ?>" 
           data-message="<?= htmlspecialchars($flash['message']); ?>">
      </div>
    <?php endif; ?>
