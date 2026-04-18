<!doctype html>
<html lang="es">

<head>
  <title>Login - Sistema Web</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="<?= $public_base; ?>css/style.css">
  <link rel="icon" type="image/x-icon" href="<?= $public_base; ?>img/deadpool.ico">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
</head>

<body class="login-body">
  <main class="container-login">
    <div class="card mb-3" id="tarjeta">
      <div class="card-body">
        <div class="text-center mb-4">
          <h2 class="fw-bold text-dark">BIENVENIDO</h2>
          <p class="text-muted small">Ingrese sus credenciales</p>
        </div>

        <?php if (!empty($mensaje)) : ?>
          <div class="alert alert-danger text-center py-2 mb-3" role="alert">
            <small><?= $mensaje; ?></small>
          </div>
        <?php endif; ?>

        <form action="<?= $formAction; ?>" method="post">
          <div class="mb-3">
            <label for="usuario" class="form-label small fw-bold">Usuario</label>
            <div class="input-group">
              <span class="input-group-text"><i class="bi bi-person-circle"></i></span>
              <input type="text" class="form-control" name="usuario" id="usuario" placeholder="Usuario" required autofocus>
            </div>
          </div>

          <div class="mb-4">
            <label for="password" class="form-label small fw-bold">Contraseña</label>
            <div class="input-group">
              <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
              <input type="password" class="form-control" name="password" id="password" placeholder="Contraseña" required>
            </div>
          </div>

          <div class="d-grid">
            <button type="submit" class="btn btn-primary py-2 fw-bold shadow-sm">INICIAR SESIÓN</button>
          </div>
        </form>
      </div>
    </div>

    <!-- Animaciones -->
    <div class="square"></div><div class="square"></div><div class="square"></div>
    <div class="circle"></div><div class="circle"></div><div class="circle"></div>
    <div class="triangle"></div><div class="triangle"></div><div class="triangle"></div>
  </main>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/3.2.1/anime.min.js"></script>
  <script src="<?= $public_base; ?>js/main.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>