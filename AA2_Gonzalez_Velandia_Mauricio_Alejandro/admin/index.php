<?php
require_once '../includes/auth.php';
require_once '../config/database.php';
require_once '../models/Producto.php';
require_once '../models/Categoria.php';
require_once '../models/Usuario.php';
require_once '../models/Pedido.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$database = new Database();
$pdo = $database->getConnection();

$producto = new Producto($pdo);
$categoria = new Categoria($pdo);
$usuario = new Usuario($pdo);
$pedido = new Pedido($pdo);

// Obtener estadísticas reales
$productos_stats = $producto->getStats();
$categorias_stats = $categoria->getStats();
$usuarios_stats = $usuario->getStats();
$pedidos_stats = $pedido->getStats();

// Obtener datos recientes
$productos_recientes = $producto->getRecent(5);
$usuarios_recientes = $usuario->getRecent(5);
$pedidos_recientes = $pedido->getRecent(5);

// Calcular stock total
$stock_total = $productos_stats['stock_total'] ?? 0;

include '../views/admin/layout.php';
?>

<style>
    .dashboard-wrapper {
        animation: fadeInUp 0.8s ease-out;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .stat-card {
        background: #ffffff;
        border-radius: 8px;
        border: 1px solid #e5e5e7;
        padding: 1rem;
        text-align: center;
        transition: all 0.2s ease;
        position: relative;
        overflow: hidden;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        color: white;
        margin: 0 auto 0.75rem auto;
    }

    .stat-content {
        text-align: center;
    }

    .stat-number {
        font-size: 1.75rem;
        font-weight: 700;
        color: #007AFF;
        margin-bottom: 0.25rem;
        display: block;
    }

    .stat-label {
        font-size: 0.75rem;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.5rem;
    }

    .stat-change {
        font-size: 0.625rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.25rem;
    }

    .stat-change.positive {
        color: #34C759;
    }

    .stat-change.negative {
        color: #FF3B30;
    }

    .dashboard-content {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
        margin-top: 1rem;
    }

    .recent-section {
        background: #ffffff;
        border-radius: 8px;
        border: 1px solid #e5e5e7;
        padding: 1rem;
    }

    .recent-section h3 {
        font-size: 1rem;
        font-weight: 600;
        color: #1d1d1f;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .recent-table {
        width: 100%;
    }

    .recent-table th {
        font-size: 0.75rem;
        font-weight: 600;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 0.5rem 0;
        border-bottom: 1px solid #e5e5e7;
        text-align: left;
    }

    .recent-table td {
        padding: 0.5rem 0;
        border-bottom: 1px solid #f3f4f6;
        font-size: 0.875rem;
        color: #374151;
    }

    .recent-table tr:last-child td {
        border-bottom: none;
    }

    .product-icon {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background: #f3f4f6;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        color: #6b7280;
    }

    .quick-actions {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 0.75rem;
        margin-top: 1rem;
    }

    .action-btn {
        background: #ffffff;
        border: 1px solid #e5e5e7;
        border-radius: 8px;
        padding: 0.75rem 1rem;
        text-decoration: none;
        color: #374151;
        font-weight: 600;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.875rem;
    }

    .action-btn:hover {
        background: #f8f9fa;
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .action-btn i {
        font-size: 1rem;
        color: #007AFF;
    }

    .empty-state {
        text-align: center;
        padding: 2rem;
        color: #6b7280;
    }

    .empty-state i {
        font-size: 2rem;
        margin-bottom: 0.5rem;
        color: #d1d5db;
    }

    .badge {
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.625rem;
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
</style>

<div class="dashboard-wrapper">
    <div class="stats-grid">
        <div class="stat-card" style="--i: 1">
            <div class="stat-icon" style="background: linear-gradient(135deg, #007AFF, #00D4FF);">
                <i class="fas fa-box"></i>
            </div>
            <div class="stat-content">
                <div class="stat-number"><?php echo $productos_stats['total_productos']; ?></div>
                <div class="stat-label">Total Productos</div>
                <div class="stat-change positive">
                    <i class="fas fa-arrow-up"></i> +<?php echo $productos_stats['productos_activos']; ?> activos
                </div>
            </div>
        </div>

        <div class="stat-card success" style="--i: 2">
            <div class="stat-icon" style="background: linear-gradient(135deg, #34C759, #30A46C);">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-content">
                <div class="stat-number"><?php echo $productos_stats['productos_activos']; ?></div>
                <div class="stat-label">Productos Activos</div>
                <div class="stat-change positive">
                    <i class="fas fa-arrow-up"></i> +<?php echo $productos_stats['productos_destacados']; ?> destacados
                </div>
            </div>
        </div>

        <div class="stat-card warning" style="--i: 3">
            <div class="stat-icon" style="background: linear-gradient(135deg, #FF9500, #FFB800);">
                <i class="fas fa-star"></i>
            </div>
            <div class="stat-content">
                <div class="stat-number"><?php echo $productos_stats['productos_destacados']; ?></div>
                <div class="stat-label">Productos Destacados</div>
                <div class="stat-change positive">
                    <i class="fas fa-arrow-up"></i> +<?php echo round($productos_stats['precio_promedio']); ?> COP promedio
                </div>
            </div>
        </div>

        <div class="stat-card error" style="--i: 4">
            <div class="stat-icon" style="background: linear-gradient(135deg, #FF3B30, #FF6B6B);">
                <i class="fas fa-dollar-sign"></i>
            </div>
            <div class="stat-content">
                <div class="stat-number">$<?php echo number_format($productos_stats['precio_promedio'], 0, ',', '.'); ?></div>
                <div class="stat-label">Precio Promedio (COP)</div>
                <div class="stat-change positive">
                    <i class="fas fa-arrow-up"></i> +<?php echo number_format($stock_total, 0, ',', '.'); ?> stock total
                </div>
            </div>
        </div>

        <div class="stat-card" style="--i: 5">
            <div class="stat-icon" style="background: linear-gradient(135deg, #5856D6, #8E8E93);">
                <i class="fas fa-tags"></i>
            </div>
            <div class="stat-content">
                <div class="stat-number"><?php echo $categorias_stats['total_categorias']; ?></div>
                <div class="stat-label">Total Categorías</div>
                <div class="stat-change positive">
                    <i class="fas fa-arrow-up"></i> +<?php echo $categorias_stats['categorias_activas']; ?> activas
                </div>
            </div>
        </div>

        <div class="stat-card success" style="--i: 6">
            <div class="stat-icon" style="background: linear-gradient(135deg, #34C759, #30A46C);">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-content">
                <div class="stat-number"><?php echo $usuarios_stats['total_usuarios']; ?></div>
                <div class="stat-label">Total Usuarios</div>
                <div class="stat-change positive">
                    <i class="fas fa-arrow-up"></i> +<?php echo $usuarios_stats['usuarios_activos']; ?> activos
                </div>
            </div>
        </div>

        <div class="stat-card warning" style="--i: 7">
            <div class="stat-icon" style="background: linear-gradient(135deg, #FF9500, #FFB800);">
                <i class="fas fa-user-check"></i>
            </div>
            <div class="stat-content">
                <div class="stat-number"><?php echo $usuarios_stats['usuarios_admin']; ?></div>
                <div class="stat-label">Administradores</div>
                <div class="stat-change positive">
                    <i class="fas fa-arrow-up"></i> +<?php echo $usuarios_stats['usuarios_normales']; ?> usuarios
                </div>
            </div>
        </div>

        <div class="stat-card error" style="--i: 8">
            <div class="stat-icon" style="background: linear-gradient(135deg, #FF3B30, #FF6B6B);">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <div class="stat-content">
                <div class="stat-number"><?php echo $pedidos_stats['total_pedidos']; ?></div>
                <div class="stat-label">Total Pedidos</div>
                <div class="stat-change positive">
                    <i class="fas fa-arrow-up"></i> +$<?php echo number_format($pedidos_stats['ingresos_totales'], 0, ',', '.'); ?> ingresos
                </div>
            </div>
        </div>
    </div>

    <div class="dashboard-content">
        <div class="recent-section">
            <h3><i class="fas fa-box"></i> Productos Recientes</h3>
            <?php if (!empty($productos_recientes)): ?>
                <table class="recent-table">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Precio</th>
                            <th>Stock</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($productos_recientes as $prod): ?>
                            <tr>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                                        <div class="product-icon">
                                            <i class="fas fa-image"></i>
                                        </div>
                                        <div>
                                            <div style="font-weight: 600; font-size: 0.75rem;"><?php echo htmlspecialchars($prod['nombre']); ?></div>
                                            <div style="font-size: 0.625rem; color: #6b7280;"><?php echo htmlspecialchars(substr($prod['descripcion'], 0, 30)) . '...'; ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td style="font-weight: 600; color: #34C759;">$<?php echo number_format($prod['precio'], 0, ',', '.'); ?></td>
                                <td><?php echo $prod['stock']; ?></td>
                                <td>
                                    <?php if ($prod['activo']): ?>
                                        <span class="badge badge-success">Activo</span>
                                    <?php else: ?>
                                        <span class="badge badge-error">Inactivo</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-box-open"></i>
                    <p>No hay productos registrados</p>
                </div>
            <?php endif; ?>
        </div>

        <div class="recent-section">
            <h3><i class="fas fa-shopping-cart"></i> Pedidos Recientes</h3>
            <?php if (!empty($pedidos_recientes)): ?>
                <table class="recent-table">
                    <thead>
                        <tr>
                            <th>Cliente</th>
                            <th>Total</th>
                            <th>Estado</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pedidos_recientes as $ped): ?>
                            <tr>
                                <td>
                                    <div>
                                        <div style="font-weight: 600; font-size: 0.75rem;"><?php echo htmlspecialchars($ped['usuario_nombre'] . ' ' . $ped['usuario_apellido']); ?></div>
                                        <div style="font-size: 0.625rem; color: #6b7280;"><?php echo htmlspecialchars($ped['usuario_email']); ?></div>
                                    </div>
                                </td>
                                <td style="font-weight: 600; color: #34C759;">$<?php echo number_format($ped['total'], 0, ',', '.'); ?></td>
                                <td>
                                    <?php
                                    $estado_class = '';
                                    switch($ped['estado']) {
                                        case 'pendiente': $estado_class = 'badge-warning'; break;
                                        case 'procesando': $estado_class = 'badge-info'; break;
                                        case 'enviado': $estado_class = 'badge-info'; break;
                                        case 'entregado': $estado_class = 'badge-success'; break;
                                        case 'cancelado': $estado_class = 'badge-error'; break;
                                        default: $estado_class = 'badge-warning';
                                    }
                                    ?>
                                    <span class="badge <?php echo $estado_class; ?>"><?php echo ucfirst($ped['estado']); ?></span>
                                </td>
                                <td style="font-size: 0.75rem; color: #6b7280;"><?php echo date('d/m/Y', strtotime($ped['fecha_pedido'])); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-shopping-cart"></i>
                    <p>No hay pedidos registrados</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="quick-actions">
        <a href="productos.php" class="action-btn">
            <i class="fas fa-plus"></i>
            Nuevo Producto
        </a>
        <a href="categorias.php" class="action-btn">
            <i class="fas fa-tags"></i>
            Nueva Categoría
        </a>
        <a href="usuarios.php" class="action-btn">
            <i class="fas fa-user-plus"></i>
            Nuevo Usuario
        </a>
        <a href="pedidos.php" class="action-btn">
            <i class="fas fa-shopping-cart"></i>
            Nuevo Pedido
        </a>
    </div>
</div>

<?php include '../views/admin/layout_end.php'; ?>
