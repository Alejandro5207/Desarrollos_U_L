<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Panel de Administración</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'SF Pro Display', 'Helvetica Neue', sans-serif;
            background: #f5f5f7;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #1d1d1f;
        }

        .login-container {
            background: #ffffff;
            border-radius: 12px;
            border: 1px solid #e5e5e7;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 3rem;
            width: 100%;
            max-width: 400px;
            margin: 1rem;
        }

        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .login-header h1 {
            color: #1d1d1f;
            font-size: 2rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .login-header p {
            color: #86868b;
            font-size: 1rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            font-weight: 500;
            color: #1d1d1f;
            margin-bottom: 0.5rem;
        }

        .form-input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid #d1d1d6;
            border-radius: 8px;
            font-size: 0.875rem;
            background: #ffffff;
            color: #1d1d1f;
            transition: all 0.2s ease;
        }

        .form-input::placeholder {
            color: #86868b;
        }

        .form-input:focus {
            outline: none;
            border-color: #007AFF;
            box-shadow: 0 0 0 3px rgba(0, 122, 255, 0.1);
        }

        .input-group {
            position: relative;
        }

        .input-group i {
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            color: #86868b;
            font-size: 1rem;
        }

        .input-group .form-input {
            padding-left: 2.5rem;
        }

        .btn {
            width: 100%;
            padding: 0.75rem 1rem;
            background: #007AFF;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn:hover {
            background: #0056CC;
        }

        .alert {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        .footer {
            text-align: center;
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid #e5e5e7;
        }

        .footer p {
            color: #86868b;
            font-size: 0.875rem;
        }

        .logo {
            width: 60px;
            height: 60px;
            background: #007AFF;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            color: white;
            font-size: 1.5rem;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <div class="logo">
                <i class="fas fa-apple"></i>
            </div>
            <h1>Panel de Administración</h1>
            <p>Inicia sesión para acceder al sistema</p>
        </div>

        <?php if (isset($error)): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label class="form-label" for="email">Usuario o Correo Electrónico</label>
                <div class="input-group">
                    <i class="fas fa-user"></i>
                    <input type="text" id="email" name="email" class="form-input" required 
                           placeholder="admin o admin@tienda.com" value="<?php echo $_POST['email'] ?? ''; ?>">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="password">Contraseña</label>
                <div class="input-group">
                    <i class="fas fa-lock"></i>
                    <input type="password" id="password" name="password" class="form-input" required 
                           placeholder="Ingresa tu contraseña">
                </div>
            </div>

            <button type="submit" class="btn">
                <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
            </button>
        </form>

        <div class="footer">
            <p>Tienda Online González - Sistema de Administración</p>
            <p>Desarrollado por Mauricio Alejandro González Velandia</p>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            const emailInput = document.getElementById('email');
            const passwordInput = document.getElementById('password');

            form.addEventListener('submit', function(e) {
                if (!emailInput.value || !passwordInput.value) {
                    e.preventDefault();
                    alert('Por favor completa todos los campos');
                    return;
                }
            });

            emailInput.addEventListener('focus', function() {
                this.parentElement.style.borderColor = '#3b82f6';
            });

            emailInput.addEventListener('blur', function() {
                this.parentElement.style.borderColor = '#d1d5db';
            });

            passwordInput.addEventListener('focus', function() {
                this.parentElement.style.borderColor = '#3b82f6';
            });

            passwordInput.addEventListener('blur', function() {
                this.parentElement.style.borderColor = '#d1d5db';
            });
        });
    </script>
</body>
</html>
