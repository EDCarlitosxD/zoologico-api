<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Correo Verificado</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }
    .card {
      border: none;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      border-radius: 10px;
    }
    .card-header {
      background-color: #28a745;
      color: white;
      font-size: 1.5rem;
      text-align: center;
      border-top-left-radius: 10px;
      border-top-right-radius: 10px;
    }
    .card-body {
      text-align: center;
    }
    .btn-custom {
      background-color: #28a745;
      border: none;
      color: white;
    }
    .btn-custom:hover {
      background-color: #218838;
    }
  </style>
</head>
<body>
  <div class="card" style="width: 24rem;">
    <div class="card-header">
      ¡Correo Verificado!
    </div>
    <div class="card-body">
      <p class="card-text">Tu correo electrónico ha sido verificado con éxito.</p>
      <a href="http://localhost:4200" class="btn btn-custom btn-lg">Ir al Inicio</a>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
