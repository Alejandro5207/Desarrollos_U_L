<?php
require_once '../includes/auth.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (!empty($email) && !empty($password)) {
        $auth = new Auth();
        if ($auth->login($email, $password)) {
            header('Location: ../index.php');
            exit();
        } else {
            $error = 'Credenciales incorrectas';
        }
    } else {
        $error = 'Por favor complete todos los campos';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Tienda Online</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: #f8f9fa;
            margin: 0;
            padding: 0;
        }

        .login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: #f8f9fa;
        }

        .login-card {
            background: #ffffff;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .login-header h1 {
            color: #007AFF;
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
        }

        .login-header p {
            color: #6b7280;
            font-size: 0.875rem;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-label {
            display: block;
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
        }

        .form-input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 0.875rem;
            transition: all 0.2s ease;
        }

        .form-input:focus {
            outline: none;
            border-color: #007AFF;
            box-shadow: 0 0 0 2px rgba(0, 122, 255, 0.1);
        }

        .btn-login {
            width: 100%;
            background: #007AFF;
            color: #ffffff;
            border: none;
            padding: 0.75rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.875rem;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .btn-login:hover {
            background: #0056CC;
            transform: translateY(-1px);
        }

        .error-message {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
            padding: 0.75rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            font-size: 0.875rem;
        }

        .login-footer {
            text-align: center;
            margin-top: 1rem;
            font-size: 0.75rem;
            color: #6b7280;
        }

        .register-link {
            margin-top: 1rem;
            text-align: center;
        }

        .register-link a {
            color: #007AFF;
            text-decoration: none;
            font-weight: 600;
        }

        .register-link a:hover {
            text-decoration: underline;
        }

        .credentials-box {
            background: #f8f9fa;
            border: 1px solid #e5e5e7;
            border-radius: 8px;
            padding: 1rem;
            margin-top: 1rem;
            text-align: left;
        }

        .credentials-box h4 {
            color: #007AFF;
            font-size: 0.875rem;
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .credential-item {
            margin-bottom: 0.75rem;
            padding: 0.5rem;
            background: #ffffff;
            border-radius: 6px;
            border: 1px solid #e5e5e7;
        }

        .credential-item:last-child {
            margin-bottom: 0;
        }

        .credential-item strong {
            color: #1d1d1f;
            font-size: 0.75rem;
            display: block;
            margin-bottom: 0.25rem;
        }

        .credential-text {
            font-size: 0.75rem;
            color: #6b7280;
            display: block;
            margin-bottom: 0.125rem;
        }

        .credential-text code {
            background: #f3f4f6;
            color: #007AFF;
            padding: 0.125rem 0.25rem;
            border-radius: 3px;
            font-family: 'Monaco', 'Menlo', monospace;
            font-size: 0.7rem;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <h1><i class="fas fa-store"></i> Tienda Online</h1>
                <p>Iniciar Sesión</p>
            </div>

            <?php if (!empty($error)): ?>
                <div class="error-message">
                    <i class="fas fa-exclamation-triangle"></i> <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label class="form-label" for="email">Usuario o Email</label>
                    <input type="text" class="form-input" id="email" name="email" 
                           value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" 
                           placeholder="mauricio" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">Contraseña</label>
                    <input type="password" class="form-input" id="password" name="password" 
                           placeholder="password" required>
                </div>

                <button type="submit" class="btn-login">
                    <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
                </button>
            </form>

            <div class="register-link">
                <p>¿No tienes cuenta? <a href="register.php">Regístrate aquí</a></p>
            </div>

            <div class="login-footer">
                <div class="credentials-box">
                    <h4><i class="fas fa-info-circle"></i> Credenciales de Prueba</h4>
                    <div class="credential-item">
                        <strong>👤 Usuario Mauricio:</strong><br>
                        <span class="credential-text">Usuario: <code>mauricio</code></span><br>
                        <span class="credential-text">Contraseña: <code>password</code></span>
                    </div>
                    <div class="credential-item">
                        <strong>👤 Usuario Juan:</strong><br>
                        <span class="credential-text">Usuario: <code>juan</code></span><br>
                        <span class="credential-text">Contraseña: <code>password</code></span>
                    </div>
                    <div class="credential-item">
                        <strong>👤 Usuario María:</strong><br>
                        <span class="credential-text">Usuario: <code>maria</code></span><br>
                        <span class="credential-text">Contraseña: <code>password</code></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
