<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title ?? 'Panel de Administración'; ?> - Tienda Online</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #007AFF;
            --primary-dark: #0056CC;
            --secondary: #8E8E93;
            --success: #34C759;
            --warning: #FF9500;
            --error: #FF3B30;
            --gray-50: #F2F2F7;
            --gray-100: #E5E5EA;
            --gray-200: #D1D1D6;
            --gray-300: #C7C7CC;
            --gray-400: #AEAEB2;
            --gray-500: #8E8E93;
            --gray-600: #636366;
            --gray-700: #48484A;
            --gray-800: #3A3A3C;
            --gray-900: #1C1C1E;
            --white: #FFFFFF;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: #f8f9fa;
            color: #1d1d1f;
            line-height: 1.5;
            overflow-x: hidden;
            margin: 0;
            padding: 0;
        }

        @keyframes backgroundShift {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.8; }
        }

        .admin-container {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 240px;
            background: #ffffff;
            border-right: 1px solid #e5e5e7;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            z-index: 1000;
            animation: slideInLeft 0.6s ease-out;
            top: 0;
            left: 0;
        }

        @keyframes slideInLeft {
            from { 
                transform: translateX(-100%); 
                opacity: 0; 
            }
            to { 
                transform: translateX(0); 
                opacity: 1; 
            }
        }

        .sidebar-header {
            padding: 1rem 1rem;
            border-bottom: 1px solid #e5e5e7;
            position: relative;
            z-index: 1;
        }

        .sidebar-header h2 {
            color: #1d1d1f;
            font-size: 1.25rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            animation: fadeInUp 0.8s ease-out 0.1s both;
        }

        @keyframes fadeInUp {
            from { 
                transform: translateY(30px); 
                opacity: 0; 
            }
            to { 
                transform: translateY(0); 
                opacity: 1; 
            }
        }

        .sidebar-header h2 i {
            background: linear-gradient(135deg, #007AFF, #34C759);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-size: 1.75rem;
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        .sidebar-nav {
            padding: 1rem 0;
        }

        .nav-item {
            display: flex;
            align-items: center;
            padding: 0.5rem 1rem;
            color: #1d1d1f;
            text-decoration: none;
            transition: all 0.3s ease;
            margin: 0.125rem 0.75rem;
            border-radius: 8px;
            font-weight: 500;
            position: relative;
            overflow: hidden;
            animation: slideInLeft 0.6s ease-out calc(0.05s * var(--i)) both;
            cursor: pointer;
            font-size: 0.875rem;
        }

        .nav-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(0, 122, 255, 0.1), rgba(0, 122, 255, 0.05));
            opacity: 0;
            transition: all 0.4s ease;
            transform: scaleX(0);
            transform-origin: left;
        }

        .nav-item::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            width: 4px;
            height: 0;
            background: linear-gradient(135deg, #007AFF, #34C759);
            border-radius: 0 4px 4px 0;
            transition: all 0.4s ease;
            transform: translateY(-50%);
        }

        .nav-item:hover {
            color: #007AFF;
            background: #f5f5f7;
            transform: translateX(2px);
        }

        .nav-item:hover::before {
            opacity: 1;
            transform: scaleX(1);
        }

        .nav-item:hover::after {
            height: 60%;
        }

        .nav-item.active {
            background: #007AFF;
            color: #ffffff;
            transform: translateX(2px);
        }

        .nav-item.active::before {
            opacity: 1;
            transform: scaleX(1);
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.2), rgba(255, 255, 255, 0.1));
        }

        .nav-item.active::after {
            height: 80%;
            background: linear-gradient(135deg, #ffffff, rgba(255, 255, 255, 0.8));
        }

        .nav-item i {
            width: 18px;
            margin-right: 0.5rem;
            font-size: 0.875rem;
            transition: all 0.2s ease;
            position: relative;
            z-index: 1;
        }

        .nav-item:hover i {
            color: #007AFF;
        }

        .nav-item.active i {
            color: #ffffff;
        }

        .nav-item span {
            position: relative;
            z-index: 1;
            transition: all 0.3s ease;
        }

        .nav-item:hover span {
            font-weight: 600;
        }

        .main-content {
            margin-left: 240px;
            padding: 0.75rem;
            animation: slideInRight 0.6s ease-out;
            min-height: 100vh;
            position: relative;
            z-index: 1;
            width: calc(100% - 240px);
        }

        @keyframes slideInRight {
            from { 
                transform: translateX(100%); 
                opacity: 0; 
            }
            to { 
                transform: translateX(0); 
                opacity: 1; 
            }
        }

        .header {
            background: #ffffff;
            padding: 0.75rem 1rem;
            border-bottom: 1px solid #e5e5e7;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            margin: -0.75rem -0.75rem 0.75rem -0.75rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-radius: 0 0 12px 12px;
            position: relative;
            overflow: hidden;
            animation: fadeInDown 0.6s ease-out;
        }

        @keyframes fadeInDown {
            from { 
                transform: translateY(-50px); 
                opacity: 0; 
            }
            to { 
                transform: translateY(0); 
                opacity: 1; 
            }
        }

        .header h1 {
            color: #1d1d1f;
            font-size: 1.25rem;
            font-weight: 600;
            position: relative;
            z-index: 1;
        }

        .user-menu {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: #1d1d1f;
            background: #f5f5f7;
            padding: 0.5rem 1rem;
            border-radius: 12px;
            transition: all 0.3s ease;
            position: relative;
            z-index: 1;
        }

        .user-info:hover {
            background: #e5e5ea;
            transform: translateY(-2px);
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.2s ease;
            border: none;
            cursor: pointer;
            font-size: 0.875rem;
        }

        .btn-danger {
            background: #FF3B30;
            color: #ffffff;
        }

        .btn-danger:hover {
            background: #d70015;
            transform: translateY(-1px);
        }

        .alert {
            padding: 1rem 1.5rem;
            border-radius: 12px;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 500;
            animation: slideInDown 0.5s ease-out;
        }

        .alert-success {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #bbf7d0;
        }

        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        .card {
            background: #ffffff;
            border-radius: 12px;
            border: 1px solid #e5e5e7;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: all 0.3s ease;
            animation: fadeInUp 0.6s ease-out 0.2s both;
            position: relative;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .card-header {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #e5e5e7;
            background: #f8f9fa;
            position: relative;
            z-index: 1;
        }

        .card-title {
            color: #1d1d1f;
            font-size: 1.125rem;
            font-weight: 600;
            margin: 0;
        }

        .card-body {
            padding: 1.5rem;
            position: relative;
            z-index: 1;
        }

        .btn-primary {
            background: #007AFF;
            color: #ffffff;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
            font-size: 0.875rem;
        }

        .btn-primary:hover {
            background: #0056CC;
            transform: translateY(-1px);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.1);
            color: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
        }

        .btn-success {
            background: #34C759;
            color: #ffffff;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-success:hover {
            background: #30b050;
            transform: translateY(-2px);
        }

        .btn-warning {
            background: #FF9500;
            color: #ffffff;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-warning:hover {
            background: #e6850e;
            transform: translateY(-2px);
        }

        .btn-danger {
            background: #FF3B30;
            color: #ffffff;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-danger:hover {
            background: #d70015;
            transform: translateY(-2px);
        }

        .badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .badge-success {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #bbf7d0;
        }

        .badge-warning {
            background: #fef3c7;
            color: #92400e;
            border: 1px solid #fde68a;
        }

        .badge-error {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        .badge-info {
            background: #dbeafe;
            color: #1e40af;
            border: 1px solid #bfdbfe;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            font-weight: 500;
            color: #ffffff;
            margin-bottom: 0.5rem;
        }

        .form-input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            font-size: 0.875rem;
            background: rgba(255, 255, 255, 0.1);
            color: #ffffff;
            transition: all 0.2s ease;
        }

        .form-input::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }

        .form-input:focus {
            outline: none;
            border-color: #007AFF;
            box-shadow: 0 0 0 3px rgba(0, 122, 255, 0.1);
        }

        .table-responsive {
            overflow-x: auto;
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th {
            color: #374151;
            font-weight: 600;
            padding: 0.75rem;
            border-bottom: 1px solid #e5e5e7;
            text-align: left;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            background: #f8f9fa;
        }

        .table td {
            padding: 0.75rem;
            border-bottom: 1px solid #f3f4f6;
            color: #374151;
            vertical-align: middle;
            transition: all 0.2s ease;
        }

        .table tr:hover {
            background: #f8f9fa;
        }

        .table tr:hover td {
            color: #1d1d1f;
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .main-content {
                margin-left: 0;
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <aside class="sidebar">
            <div class="sidebar-header">
                <h2><i class="fas fa-apple"></i> Tienda Online</h2>
            </div>
            <nav class="sidebar-nav">
                <a href="index.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>" style="--i: 1">
                    <i class="fas fa-chart-line"></i> <span>Dashboard</span>
                </a>
                <a href="productos.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'productos.php' ? 'active' : ''; ?>" style="--i: 2">
                    <i class="fas fa-cube"></i> <span>Productos</span>
                </a>
                <a href="categorias.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'categorias.php' ? 'active' : ''; ?>" style="--i: 3">
                    <i class="fas fa-folder"></i> <span>Categorías</span>
                </a>
                <a href="usuarios.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'usuarios.php' ? 'active' : ''; ?>" style="--i: 4">
                    <i class="fas fa-user"></i> <span>Usuarios</span>
                </a>
                <a href="pedidos.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'pedidos.php' ? 'active' : ''; ?>" style="--i: 5">
                    <i class="fas fa-shopping-bag"></i> <span>Pedidos</span>
                </a>
                <a href="configuracion.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'configuracion.php' ? 'active' : ''; ?>" style="--i: 6">
                    <i class="fas fa-gear"></i> <span>Configuración</span>
                </a>
                <a href="../index.php" class="nav-item" style="--i: 7" target="_blank">
                    <i class="fas fa-store"></i> <span>Ver Tienda</span>
                </a>
                <a href="logout.php" class="nav-item" style="--i: 8">
                    <i class="fas fa-right-from-bracket"></i> <span>Cerrar Sesión</span>
                </a>
            </nav>
        </aside>

        <main class="main-content">
            <div class="header">
                <h1><?php echo $page_title ?? 'Panel de Administración'; ?></h1>
                <div class="user-menu">
                    <a href="../index.php" class="btn btn-success" target="_blank">
                        <i class="fas fa-store"></i> Ver Tienda
                    </a>
                    <div class="user-info">
                        <i class="fas fa-user-circle"></i>
                        <span><?php echo $_SESSION['user_name'] ?? 'Usuario'; ?></span>
                    </div>
                    <a href="logout.php" class="btn btn-danger">
                        <i class="fas fa-right-from-bracket"></i> Salir
                    </a>
                </div>
            </div>

            <?php if (isset($success_message)): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> <?php echo $success_message; ?>
                </div>
            <?php endif; ?>

            <?php if (isset($error_message)): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i> <?php echo $error_message; ?>
                </div>
            <?php endif; ?>

            <div class="content-wrapper">