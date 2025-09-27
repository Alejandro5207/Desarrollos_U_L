<?php
require_once '../includes/auth.php';
require_once '../config/database.php';
require_once '../models/Pedido.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$database = new Database();
$pdo = $database->getConnection();
$pedido = new Pedido($pdo);

// Obtener pedidos del usuario actual
$user_id = $_SESSION['user_id'];
$pedidos = $pedido->getByUsuario($user_id);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Cuenta - Tienda Online</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: #f8f9fa;
            color: #1d1d1f;
            line-height: 1.6;
        }

        .header {
            background: #ffffff;
            border-bottom: 1px solid #e5e5e7;
            padding: 1rem 0;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .header-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 1.5rem;
            font-weight: 700;
            color: #007AFF;
            text-decoration: none;
        }

        .nav {
            display: flex;
            gap: 2rem;
            align-items: center;
        }

        .nav a {
            color: #374151;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s ease;
        }

        .nav a:hover {
            color: #007AFF;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .user-name {
            font-weight: 600;
            color: #1d1d1f;
        }

        .btn {
            padding: 0.5rem 1rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.2s ease;
            border: none;
            cursor: pointer;
            font-size: 0.875rem;
        }

        .btn-primary {
            background: #007AFF;
            color: #ffffff;
        }

        .btn-primary:hover {
            background: #0056CC;
            transform: translateY(-1px);
        }

        .btn-secondary {
            background: #f3f4f6;
            color: #374151;
            border: 1px solid #d1d5db;
        }

        .btn-secondary:hover {
            background: #e5e7eb;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem 1rem;
        }

        .page-header {
            margin-bottom: 2rem;
        }

        .page-title {
            font-size: 2rem;
            font-weight: 700;
            color: #1d1d1f;
            margin-bottom: 0.5rem;
        }

        .page-subtitle {
            color: #6b7280;
            font-size: 1rem;
        }

        .orders-section {
            background: #ffffff;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .section-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #1d1d1f;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .orders-table {
            width: 100%;
            border-collapse: collapse;
        }

        .orders-table th {
            background: #f8f9fa;
            color: #374151;
            font-weight: 600;
            padding: 0.75rem;
            text-align: left;
            border-bottom: 1px solid #e5e5e7;
            font-size: 0.875rem;
        }

        .orders-table td {
            padding: 0.75rem;
            border-bottom: 1px solid #f3f4f6;
            color: #374151;
            font-size: 0.875rem;
        }

        .orders-table tr:hover {
            background: #f8f9fa;
        }

        .order-id {
            font-weight: 600;
            color: #007AFF;
        }

        .order-total {
            font-weight: 600;
            color: #34C759;
        }

        .order-date {
            color: #6b7280;
        }

        .status-badge {
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.625rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-pendiente {
            background: #fef3c7;
            color: #92400e;
        }

        .status-procesando {
            background: #dbeafe;
            color: #1e40af;
        }

        .status-enviado {
            background: #dbeafe;
            color: #1e40af;
        }

        .status-entregado {
            background: #dcfce7;
            color: #166534;
        }

        .status-cancelado {
            background: #fee2e2;
            color: #991b1b;
        }

        .empty-state {
            text-align: center;
            padding: 3rem 2rem;
            color: #6b7280;
        }

        .empty-state i {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: #d1d5db;
        }

        .empty-state h3 {
            font-size: 1.25rem;
            margin-bottom: 0.5rem;
            color: #374151;
        }

        .empty-state p {
            margin-bottom: 1.5rem;
        }

        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                gap: 1rem;
            }

            .nav {
                gap: 1rem;
            }

            .orders-table {
                font-size: 0.75rem;
            }

            .orders-table th,
            .orders-table td {
                padding: 0.5rem;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="header-content">
            <a href="../index.php" class="logo">
                <i class="fas fa-store"></i> Tienda Online
            </a>
            <nav class="nav">
                <a href="../index.php">Inicio</a>
                <a href="../index.php#productos">Productos</a>
                <a href="../index.php#categorias">Categorías</a>
                <a href="carrito.php">Carrito</a>
            </nav>
            <div class="user-info">
                <span class="user-name">Hola, <?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
                <a href="logout.php" class="btn btn-secondary">
                    <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                </a>
            </div>
        </div>
    </header>

    <div class="container">
        <div class="page-header">
            <h1 class="page-title">Mi Cuenta</h1>
            <p class="page-subtitle">Gestiona tus pedidos y información personal</p>
        </div>

        <div class="orders-section">
            <h2 class="section-title">
                <i class="fas fa-shopping-cart"></i> Mis Pedidos
            </h2>

            <?php if (!empty($pedidos)): ?>
                <table class="orders-table">
                    <thead>
                        <tr>
                            <th>Pedido #</th>
                            <th>Fecha</th>
                            <th>Total</th>
                            <th>Estado</th>
                            <th>Método de Pago</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pedidos as $ped): ?>
                            <tr>
                                <td class="order-id">#<?php echo $ped['id']; ?></td>
                                <td class="order-date"><?php echo date('d/m/Y H:i', strtotime($ped['fecha_pedido'])); ?></td>
                                <td class="order-total">$<?php echo number_format($ped['total'], 0, ',', '.'); ?> COP</td>
                                <td>
                                    <span class="status-badge status-<?php echo $ped['estado']; ?>">
                                        <?php echo ucfirst($ped['estado']); ?>
                                    </span>
                                </td>
                                <td><?php echo htmlspecialchars($ped['metodo_pago']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-shopping-cart"></i>
                    <h3>No tienes pedidos aún</h3>
                    <p>Explora nuestros productos y realiza tu primera compra</p>
                    <a href="../index.php" class="btn btn-primary">
                        <i class="fas fa-shopping-bag"></i> Ver Productos
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
