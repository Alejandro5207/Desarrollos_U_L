<?php
require_once '../includes/auth.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (!empty($email) && !empty($password)) {
        $auth = new Auth();
        if ($auth->login($email, $password)) {
            header('Location: index.php');
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
</style>

<div class="login-container">
    <div class="login-card">
        <div class="login-header">
            <h1><i class="fas fa-store"></i> Tienda Online</h1>
            <p>Panel de Administración</p>
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

        <div class="login-footer">
            <p>Credenciales por defecto:</p>
            <p><strong>Usuario:</strong> mauricio | <strong>Contraseña:</strong> password</p>
            <p><strong>Usuario:</strong> admin | <strong>Contraseña:</strong> password</p>
        </div>
    </div>
</div>

</body>
</html>