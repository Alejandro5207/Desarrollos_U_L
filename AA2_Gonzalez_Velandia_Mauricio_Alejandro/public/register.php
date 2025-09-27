<?php
require_once '../config/database.php';
require_once '../models/Usuario.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'] ?? '';
    $apellido = $_POST['apellido'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $telefono = $_POST['telefono'] ?? '';
    $direccion = $_POST['direccion'] ?? '';
    
    if (empty($nombre) || empty($apellido) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = 'Por favor complete todos los campos obligatorios';
    } elseif ($password !== $confirm_password) {
        $error = 'Las contraseñas no coinciden';
    } elseif (strlen($password) < 6) {
        $error = 'La contraseña debe tener al menos 6 caracteres';
    } else {
        try {
            $database = new Database();
            $pdo = $database->getConnection();
            $usuario = new Usuario($pdo);
            
            // Verificar si el email ya existe
            $existing_user = $usuario->getByEmail($email);
            if ($existing_user) {
                $error = 'El email ya está registrado';
            } else {
                $data = [
                    'nombre' => $nombre,
                    'apellido' => $apellido,
                    'email' => $email,
                    'password' => password_hash($password, PASSWORD_DEFAULT),
                    'telefono' => $telefono,
                    'direccion' => $direccion,
                    'rol' => 'usuario',
                    'activo' => 1
                ];
                
                if ($usuario->create($data)) {
                    $success = 'Usuario registrado exitosamente. Ya puedes iniciar sesión.';
                } else {
                    $error = 'Error al registrar el usuario';
                }
            }
        } catch (Exception $e) {
            $error = 'Error del servidor: ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Tienda Online</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: #f8f9fa;
            margin: 0;
            padding: 0;
        }

        .register-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: #f8f9fa;
            padding: 2rem 0;
        }

        .register-card {
            background: #ffffff;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
        }

        .register-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .register-header h1 {
            color: #007AFF;
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
        }

        .register-header p {
            color: #6b7280;
            font-size: 0.875rem;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
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

        .btn-register {
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

        .btn-register:hover {
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

        .success-message {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #bbf7d0;
            padding: 0.75rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            font-size: 0.875rem;
        }

        .login-link {
            margin-top: 1rem;
            text-align: center;
        }

        .login-link a {
            color: #007AFF;
            text-decoration: none;
            font-weight: 600;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        .required {
            color: #ef4444;
        }

        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-card">
            <div class="register-header">
                <h1><i class="fas fa-user-plus"></i> Registro</h1>
                <p>Crea tu cuenta en Tienda Online</p>
            </div>

            <?php if (!empty($error)): ?>
                <div class="error-message">
                    <i class="fas fa-exclamation-triangle"></i> <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($success)): ?>
                <div class="success-message">
                    <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($success); ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="nombre">Nombre <span class="required">*</span></label>
                        <input type="text" class="form-input" id="nombre" name="nombre" 
                               value="<?php echo htmlspecialchars($_POST['nombre'] ?? ''); ?>" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="apellido">Apellido <span class="required">*</span></label>
                        <input type="text" class="form-input" id="apellido" name="apellido" 
                               value="<?php echo htmlspecialchars($_POST['apellido'] ?? ''); ?>" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="email">Email <span class="required">*</span></label>
                    <input type="email" class="form-input" id="email" name="email" 
                           value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="password">Contraseña <span class="required">*</span></label>
                        <input type="password" class="form-input" id="password" name="password" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="confirm_password">Confirmar Contraseña <span class="required">*</span></label>
                        <input type="password" class="form-input" id="confirm_password" name="confirm_password" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="telefono">Teléfono</label>
                    <input type="tel" class="form-input" id="telefono" name="telefono" 
                           value="<?php echo htmlspecialchars($_POST['telefono'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label class="form-label" for="direccion">Dirección</label>
                    <textarea class="form-input" id="direccion" name="direccion" rows="3"><?php echo htmlspecialchars($_POST['direccion'] ?? ''); ?></textarea>
                </div>

                <button type="submit" class="btn-register">
                    <i class="fas fa-user-plus"></i> Registrarse
                </button>
            </form>

            <div class="login-link">
                <p>¿Ya tienes cuenta? <a href="login.php">Inicia sesión aquí</a></p>
            </div>
        </div>
    </div>
</body>
</html>
