<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Link Expirado</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .error-box {
            background: white;
            padding: 40px;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            max-width: 400px;
        }
        .error-icon {
            font-size: 64px;
            color: #ef4444;
            margin-bottom: 20px;
        }
        .error-box h2 {
            color: #1f2937;
            margin-bottom: 10px;
        }
        .error-box p {
            color: #6b7280;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="error-box">
        <div class="error-icon">
            <i class="fas fa-exclamation-circle"></i>
        </div>
        <h2>Link Expirado</h2>
        <p>{{ $mensaje }}</p>
        <p style="font-size: 12px; color: #9ca3af;">Por favor, solicita un nuevo link a la institución</p>
    </div>
</body>
</html>
