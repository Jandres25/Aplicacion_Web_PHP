<!doctype html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= htmlspecialchars((string)$errorCode, ENT_QUOTES, 'UTF-8'); ?> - <?= htmlspecialchars((string)$errorTitle, ENT_QUOTES, 'UTF-8'); ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
  <link rel="stylesheet" href="<?= htmlspecialchars((string)$public_base, ENT_QUOTES, 'UTF-8'); ?>css/style.css">
  <link rel="icon" type="image/x-icon" href="<?= htmlspecialchars((string)$public_base, ENT_QUOTES, 'UTF-8'); ?>img/deadpool.ico">
</head>

<body class="error-page-body">
  <main class="container d-flex justify-content-center align-items-center min-vh-100 py-4">
    <section class="card shadow-sm border-0 error-page-card">
      <div class="card-body text-center p-4 p-md-5">
        <p class="display-4 fw-bold text-danger mb-2"><?= htmlspecialchars((string)$errorCode, ENT_QUOTES, 'UTF-8'); ?></p>
        <h1 class="h3 fw-bold mb-3"><?= htmlspecialchars((string)$errorTitle, ENT_QUOTES, 'UTF-8'); ?></h1>
        <p class="text-muted mb-4"><?= htmlspecialchars((string)$errorMessage, ENT_QUOTES, 'UTF-8'); ?></p>
        <a href="<?= htmlspecialchars((string)$public_base, ENT_QUOTES, 'UTF-8'); ?>" class="btn btn-primary px-4">Volver al inicio</a>
      </div>
    </section>
  </main>
</body>

</html>
