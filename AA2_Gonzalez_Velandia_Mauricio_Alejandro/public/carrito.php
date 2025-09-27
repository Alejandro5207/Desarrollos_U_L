<?php
require_once '../includes/auth.php';
require_once '../config/database.php';
require_once '../models/Producto.php';
require_once '../models/Pedido.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$database = new Database();
$pdo = $database->getConnection();
$producto = new Producto($pdo);
$pedido = new Pedido($pdo);

if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

$carrito = $_SESSION['carrito'];
$total = 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'add') {
        $producto_id = $_POST['producto_id'] ?? 0;
        $cantidad = $_POST['cantidad'] ?? 1;
        
        if ($producto_id && $cantidad > 0) {
            $producto_data = $producto->getById($producto_id);
            if (!$producto_data) {
                $error = 'Producto no encontrado';
            } else {
                $cantidad_actual = $carrito[$producto_id] ?? 0;
                $cantidad_total = $cantidad_actual + $cantidad;
                
                if ($cantidad_total > $producto_data['stock']) {
                    $error = "Stock insuficiente. Solo hay {$producto_data['stock']} unidades disponibles de {$producto_data['nombre']}";
                } else {
                    $carrito[$producto_id] = $cantidad_total;
                    $_SESSION['carrito'] = $carrito;
                    header('Location: carrito.php');
                    exit;
                }
            }
        }
    } elseif ($action === 'update') {
        $producto_id = $_POST['producto_id'] ?? 0;
        $cantidad = $_POST['cantidad'] ?? 0;
        
        if ($producto_id) {
            if ($cantidad > 0) {
                $producto_data = $producto->getById($producto_id);
                if (!$producto_data) {
                    $error = 'Producto no encontrado';
                } elseif ($cantidad > $producto_data['stock']) {
                    $error = "Stock insuficiente. Solo hay {$producto_data['stock']} unidades disponibles de {$producto_data['nombre']}";
                } else {
                    $carrito[$producto_id] = $cantidad;
                    $_SESSION['carrito'] = $carrito;
                    header('Location: carrito.php');
                    exit;
                }
            } else {
                unset($carrito[$producto_id]);
                $_SESSION['carrito'] = $carrito;
                header('Location: carrito.php');
                exit;
            }
        }
    } elseif ($action === 'remove') {
        $producto_id = $_POST['producto_id'] ?? 0;
        if ($producto_id) {
            unset($carrito[$producto_id]);
            $_SESSION['carrito'] = $carrito;
            header('Location: carrito.php');
            exit;
        }
    } elseif ($action === 'checkout') {
        // Procesar pedido
        $direccion_envio = $_POST['direccion_envio'] ?? '';
        $telefono_contacto = $_POST['telefono_contacto'] ?? '';
        $metodo_pago = $_POST['metodo_pago'] ?? 'Efectivo';
        $notas = $_POST['notas'] ?? '';
        
        if (empty($direccion_envio) || empty($telefono_contacto)) {
            $error = 'Por favor complete la dirección y teléfono de contacto';
        } elseif (empty($carrito)) {
            $error = 'El carrito está vacío';
        } else {
            $stock_insuficiente = false;
            $productos_validos = [];
            $total_pedido = 0;
            
            foreach ($carrito as $producto_id => $cantidad) {
                $prod = $producto->getById($producto_id);
                if ($prod) {
                    if ($cantidad > $prod['stock']) {
                        $stock_insuficiente = true;
                        $error = "Stock insuficiente para {$prod['nombre']}. Disponible: {$prod['stock']}, Solicitado: {$cantidad}";
                        break;
                    }
                    $productos_validos[] = [
                        'producto_id' => $producto_id,
                        'cantidad' => $cantidad,
                        'precio' => $prod['precio'],
                        'subtotal' => $prod['precio'] * $cantidad
                    ];
                    $total_pedido += $prod['precio'] * $cantidad;
                }
            }
            
            if (!$stock_insuficiente) {
                $pdo->beginTransaction();
                
                try {
                    $data = [
                        'usuario_id' => $_SESSION['user_id'],
                        'total' => $total_pedido,
                        'estado' => 'pendiente',
                        'direccion_envio' => $direccion_envio,
                        'telefono_contacto' => $telefono_contacto,
                        'notas' => $notas,
                        'metodo_pago' => $metodo_pago
                    ];
                    
                    if ($pedido->create($data)) {
                        $pedido_id = $pdo->lastInsertId();
                        
                        // Crear detalles del pedido y reducir stock
                        foreach ($productos_validos as $item) {
                            // Insertar detalle del pedido
                            $query_detalle = "INSERT INTO detalle_pedidos (pedido_id, producto_id, cantidad, precio_unitario, subtotal) 
                                            VALUES (:pedido_id, :producto_id, :cantidad, :precio_unitario, :subtotal)";
                            $stmt_detalle = $pdo->prepare($query_detalle);
                            $stmt_detalle->bindParam(':pedido_id', $pedido_id);
                            $stmt_detalle->bindParam(':producto_id', $item['producto_id']);
                            $stmt_detalle->bindParam(':cantidad', $item['cantidad']);
                            $stmt_detalle->bindParam(':precio_unitario', $item['precio']);
                            $stmt_detalle->bindParam(':subtotal', $item['subtotal']);
                            $stmt_detalle->execute();
                            
                            // Reducir stock
                            $query_stock = "UPDATE productos SET stock = stock - :cantidad WHERE id = :producto_id";
                            $stmt_stock = $pdo->prepare($query_stock);
                            $stmt_stock->bindParam(':cantidad', $item['cantidad']);
                            $stmt_stock->bindParam(':producto_id', $item['producto_id']);
                            $stmt_stock->execute();
                        }
                        
                        // Confirmar transacción
                        $pdo->commit();
                        
                        $_SESSION['carrito'] = [];
                        $success = 'Pedido realizado exitosamente. Te contactaremos pronto.';
                        $carrito = [];
                    } else {
                        $pdo->rollBack();
                        $error = 'Error al procesar el pedido';
                    }
                } catch (Exception $e) {
                    $pdo->rollBack();
                    $error = 'Error al procesar el pedido: ' . $e->getMessage();
                }
            }
        }
    }
}

// Obtener productos del carrito
$productos_carrito = [];
foreach ($carrito as $producto_id => $cantidad) {
    $prod = $producto->getById($producto_id);
    if ($prod) {
        $prod['cantidad'] = $cantidad;
        $prod['subtotal'] = $prod['precio'] * $cantidad;
        $productos_carrito[] = $prod;
        $total += $prod['subtotal'];
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito - Tienda Online</title>
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

        .btn-danger {
            background: #ef4444;
            color: #ffffff;
        }

        .btn-danger:hover {
            background: #dc2626;
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

        .cart-content {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 2rem;
        }

        .cart-items {
            background: #ffffff;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .cart-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem 0;
            border-bottom: 1px solid #f3f4f6;
        }

        .cart-item:last-child {
            border-bottom: none;
        }

        .item-image {
            width: 80px;
            height: 80px;
            background: #f3f4f6;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: #d1d5db;
        }

        .item-info {
            flex: 1;
        }

        .item-name {
            font-size: 1rem;
            font-weight: 600;
            color: #1d1d1f;
            margin-bottom: 0.25rem;
        }

        .item-price {
            font-size: 0.875rem;
            color: #34C759;
            font-weight: 600;
        }

        .item-controls {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .quantity-control {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .quantity-input {
            width: 60px;
            padding: 0.5rem;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            text-align: center;
            font-size: 0.875rem;
        }

        .item-subtotal {
            font-size: 1rem;
            font-weight: 600;
            color: #1d1d1f;
            min-width: 100px;
            text-align: right;
        }

        .cart-summary {
            background: #ffffff;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            height: fit-content;
        }

        .summary-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #1d1d1f;
            margin-bottom: 1rem;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.75rem;
            font-size: 0.875rem;
        }

        .summary-total {
            font-size: 1.125rem;
            font-weight: 700;
            color: #1d1d1f;
            border-top: 1px solid #e5e5e7;
            padding-top: 0.75rem;
            margin-top: 0.75rem;
        }

        .checkout-form {
            margin-top: 1.5rem;
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

        .form-textarea {
            min-height: 80px;
            resize: vertical;
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
            .cart-content {
                grid-template-columns: 1fr;
            }

            .cart-item {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.75rem;
            }

            .item-controls {
                width: 100%;
                justify-content: space-between;
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
                <a href="mi_cuenta.php">Mi Cuenta</a>
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
            <h1 class="page-title">Carrito de Compras</h1>
            <p class="page-subtitle">Revisa tus productos antes de finalizar la compra</p>
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

        <?php if (empty($productos_carrito)): ?>
            <div class="empty-state">
                <i class="fas fa-shopping-cart"></i>
                <h3>Tu carrito está vacío</h3>
                <p>Explora nuestros productos y agrega algunos a tu carrito</p>
                <a href="../index.php" class="btn btn-primary">
                    <i class="fas fa-shopping-bag"></i> Ver Productos
                </a>
            </div>
        <?php else: ?>
            <div class="cart-content">
                <div class="cart-items">
                    <h2 style="margin-bottom: 1rem; color: #1d1d1f;">Productos en tu carrito</h2>
                    
                    <?php foreach ($productos_carrito as $item): ?>
                        <div class="cart-item">
                            <div class="item-image">
                                <i class="fas fa-image"></i>
                            </div>
                            <div class="item-info">
                                <div class="item-name"><?php echo htmlspecialchars($item['nombre']); ?></div>
                                <div class="item-price">$<?php echo number_format($item['precio'], 0, ',', '.'); ?> COP</div>
                            </div>
                            <div class="item-controls">
                                <div class="quantity-control">
                                    <form method="POST" style="display: inline;">
                                        <input type="hidden" name="action" value="update">
                                        <input type="hidden" name="producto_id" value="<?php echo $item['id']; ?>">
                                        <input type="number" name="cantidad" value="<?php echo $item['cantidad']; ?>" 
                                               min="1" max="99" class="quantity-input" 
                                               onchange="this.form.submit()">
                                    </form>
                                </div>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="action" value="remove">
                                    <input type="hidden" name="producto_id" value="<?php echo $item['id']; ?>">
                                    <button type="submit" class="btn btn-danger" style="padding: 0.5rem;">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                            <div class="item-subtotal">
                                $<?php echo number_format($item['subtotal'], 0, ',', '.'); ?> COP
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="cart-summary">
                    <h3 class="summary-title">Resumen del Pedido</h3>
                    
                    <div class="summary-row">
                        <span>Subtotal:</span>
                        <span>$<?php echo number_format($total, 0, ',', '.'); ?> COP</span>
                    </div>
                    <div class="summary-row">
                        <span>Envío:</span>
                        <span>$15.000 COP</span>
                    </div>
                    <div class="summary-total">
                        <span>Total:</span>
                        <span>$<?php echo number_format($total + 15000, 0, ',', '.'); ?> COP</span>
                    </div>

                    <form method="POST" class="checkout-form">
                        <input type="hidden" name="action" value="checkout">
                        
                        <div class="form-group">
                            <label class="form-label" for="direccion_envio">Dirección de Envío *</label>
                            <textarea class="form-input form-textarea" id="direccion_envio" name="direccion_envio" 
                                      placeholder="Calle 123 #45-67, Ciudad" required></textarea>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="telefono_contacto">Teléfono de Contacto *</label>
                            <input type="tel" class="form-input" id="telefono_contacto" name="telefono_contacto" 
                                   placeholder="3001234567" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="metodo_pago">Método de Pago</label>
                            <select class="form-input" id="metodo_pago" name="metodo_pago">
                                <option value="Efectivo">Efectivo contra entrega</option>
                                <option value="Tarjeta de Crédito">Tarjeta de Crédito</option>
                                <option value="Transferencia Bancaria">Transferencia Bancaria</option>
                                <option value="PayPal">PayPal</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="notas">Notas Adicionales</label>
                            <textarea class="form-input form-textarea" id="notas" name="notas" 
                                      placeholder="Instrucciones especiales para la entrega..."></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary" style="width: 100%; padding: 1rem;">
                            <i class="fas fa-credit-card"></i> Finalizar Compra
                        </button>
                    </form>
                </div>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
