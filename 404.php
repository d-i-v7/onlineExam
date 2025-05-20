<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>404 - Page Not Found</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
    }
    .error-container {
      text-align: center;
    }
    .error-code {
      font-size: 120px;
      font-weight: bold;
      color: #dc3545;
    }
    .error-message {
      font-size: 24px;
      margin-bottom: 30px;
      color: #6c757d;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="row justify-content-center align-items-center min-vh-100">
      <div class="col-md-8 text-center">
        <div class="error-container">
          <div class="error-code">404</div>
          <div class="error-message">Oops! Page Not Found</div>
          <a href="dashboard.php" class="btn btn-primary">Back to Dashboard</a>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
