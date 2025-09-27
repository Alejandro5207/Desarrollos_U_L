<?php
$page_title = 'Dashboard';

// Datos hardcodeados para que funcione inmediatamente
$productos_stats = [
    'total_productos' => 45,
    'productos_activos' => 42,
    'productos_destacados' => 8,
    'precio_promedio' => 2500000
];

$categorias_stats = [
    'total_categorias' => 8,
    'categorias_activas' => 7
];

$usuarios_stats = [
    'total_usuarios' => 125,
    'usuarios_activos' => 118
];

$stock_total = 1250;

$productos_recientes = [
    [
        'nombre' => 'iPhone 15 Pro',
        'precio' => 4200000,
        'precio_anterior' => 4500000,
        'stock' => 25,
        'categoria' => 'Electrónicos',
        'estado' => 'destacado',
        'icono' => 'fas fa-mobile-alt',
        'color' => 'linear-gradient(135deg, #007AFF, #34C759)'
    ],
    [
        'nombre' => 'MacBook Air M2',
        'precio' => 5500000,
        'precio_anterior' => null,
        'stock' => 15,
        'categoria' => 'Computadoras',
        'estado' => 'normal',
        'icono' => 'fas fa-laptop',
        'color' => 'linear-gradient(135deg, #FF9500, #FF3B30)'
    ],
    [
        'nombre' => 'AirPods Pro',
        'precio' => 750000,
        'precio_anterior' => 800000,
        'stock' => 50,
        'categoria' => 'Audio',
        'estado' => 'normal',
        'icono' => 'fas fa-headphones',
        'color' => 'linear-gradient(135deg, #34C759, #007AFF)'
    ]
];

$categorias_con_productos = [
    ['nombre' => 'Electrónicos', 'productos' => 12, 'estado' => 'activo', 'icono' => 'fas fa-mobile-alt', 'color' => '#007AFF'],
    ['nombre' => 'Computadoras', 'productos' => 8, 'estado' => 'activo', 'icono' => 'fas fa-laptop', 'color' => '#FF9500'],
    ['nombre' => 'Audio', 'productos' => 15, 'estado' => 'activo', 'icono' => 'fas fa-headphones', 'color' => '#34C759'],
    ['nombre' => 'Accesorios', 'productos' => 6, 'estado' => 'activo', 'icono' => 'fas fa-keyboard', 'color' => '#8E8E93'],
    ['nombre' => 'Gaming', 'productos' => 4, 'estado' => 'activo', 'icono' => 'fas fa-gamepad', 'color' => '#FF3B30']
];

include __DIR__ . '/layout.php';
?>

<style>
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .stat-card {
        background: #ffffff;
        padding: 1rem;
        border-radius: 12px;
        border: 1px solid #e5e5e7;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        animation: fadeInUp 0.6s ease-out calc(0.05s * var(--i)) both;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.05));
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .stat-card:hover::before {
        opacity: 1;
    }

    .stat-card.success {
        background: linear-gradient(135deg, rgba(52, 199, 89, 0.2), rgba(52, 199, 89, 0.1));
        border-color: rgba(52, 199, 89, 0.3);
    }

    .stat-card.warning {
        background: linear-gradient(135deg, rgba(255, 149, 0, 0.2), rgba(255, 149, 0, 0.1));
        border-color: rgba(255, 149, 0, 0.3);
    }

    .stat-card.error {
        background: linear-gradient(135deg, rgba(255, 59, 48, 0.2), rgba(255, 59, 48, 0.1));
        border-color: rgba(255, 59, 48, 0.3);
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
        flex-shrink: 0;
    }

    .stat-content {
        flex: 1;
    }

    .stat-number {
        font-size: 1.75rem;
        font-weight: 700;
        color: #1d1d1f;
        margin-bottom: 0.25rem;
        position: relative;
        z-index: 1;
    }

    .stat-label {
        font-size: 0.75rem;
        color: #6b7280;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        position: relative;
        z-index: 1;
        margin-bottom: 0.25rem;
    }

    .stat-change {
        font-size: 0.625rem;
        font-weight: 600;
        display: flex;
        align-items: center;
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

    .recent-table {
        width: 100%;
        border-collapse: collapse;
    }

    .recent-table th {
        color: #374151;
        font-weight: 600;
        padding: 0.5rem 0;
        border-bottom: 1px solid #e5e5e7;
        text-align: left;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .recent-table td {
        padding: 0.5rem 0;
        border-bottom: 1px solid #f3f4f6;
        color: #374151;
        vertical-align: middle;
    }

    .recent-table tr:hover {
        background: #f8f9fa;
    }

    .product-icon {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1rem;
        margin-right: 0.75rem;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .quick-actions {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 0.75rem;
        margin-top: 1rem;
    }

    .action-btn {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1rem;
        background: #ffffff;
        border: 1px solid #e5e5e7;
        border-radius: 8px;
        color: #374151;
        text-decoration: none;
        transition: all 0.2s ease;
        font-weight: 500;
        position: relative;
        overflow: hidden;
        animation: fadeInUp 0.6s ease-out calc(0.05s * var(--i)) both;
        font-size: 0.875rem;
    }

    .action-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.05));
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .action-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        color: #007AFF;
        border-color: #007AFF;
    }

    .action-btn:hover::before {
        opacity: 1;
    }

    .action-btn i {
        font-size: 1rem;
        position: relative;
        z-index: 1;
    }

    .action-btn span {
        position: relative;
        z-index: 1;
    }

    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }
        
        .dashboard-content {
            grid-template-columns: 1fr;
        }
        
        .quick-actions {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="stats-grid">
    <div class="stat-card" style="--i: 1">
        <div class="stat-icon" style="background: linear-gradient(135deg, #007AFF, #00D4FF);">
            <i class="fas fa-box"></i>
        </div>
        <div class="stat-content">
            <div class="stat-number"><?php echo $productos_stats['total_productos']; ?></div>
        <div class="stat-label">Total Productos</div>
            <div class="stat-change positive">
                <i class="fas fa-arrow-up"></i> +12% este mes
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
                <i class="fas fa-arrow-up"></i> +8% este mes
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
                <i class="fas fa-arrow-up"></i> +3 nuevos
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
                <i class="fas fa-arrow-up"></i> +5% este mes
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
                <i class="fas fa-arrow-up"></i> +2 nuevas
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
                <i class="fas fa-arrow-up"></i> +15% este mes
            </div>
        </div>
    </div>
    <div class="stat-card warning" style="--i: 7">
        <div class="stat-icon" style="background: linear-gradient(135deg, #FF9500, #FFB800);">
            <i class="fas fa-user-check"></i>
    </div>
        <div class="stat-content">
            <div class="stat-number"><?php echo $usuarios_stats['usuarios_activos']; ?></div>
        <div class="stat-label">Usuarios Activos</div>
            <div class="stat-change positive">
                <i class="fas fa-arrow-up"></i> +12% este mes
            </div>
        </div>
    </div>
    <div class="stat-card error" style="--i: 8">
        <div class="stat-icon" style="background: linear-gradient(135deg, #FF3B30, #FF6B6B);">
            <i class="fas fa-warehouse"></i>
    </div>
        <div class="stat-content">
            <div class="stat-number"><?php echo number_format($stock_total, 0, ',', '.'); ?></div>
        <div class="stat-label">Stock Total</div>
            <div class="stat-change positive">
                <i class="fas fa-arrow-up"></i> +8% este mes
            </div>
        </div>
    </div>
</div>

<div class="dashboard-content">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Productos Recientes</h3>
        </div>
        <div class="card-body">
                <div class="table-responsive">
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
                            <?php foreach ($productos_recientes as $producto): ?>
                                <tr>
                                    <td>
                                <div style="display: flex; align-items: center;">
                                    <div class="product-icon" style="background: <?php echo $producto['color']; ?>">
                                        <i class="<?php echo $producto['icono']; ?>"></i>
                                    </div>
                                            <div>
                                        <div style="font-weight: 500;"><?php echo $producto['nombre']; ?></div>
                                        <div style="font-size: 0.75rem; color: rgba(255, 255, 255, 0.6);"><?php echo $producto['categoria']; ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                <div style="color: #34C759; font-weight: 500;">$<?php echo number_format($producto['precio'], 0, ',', '.'); ?></div>
                                <?php if ($producto['precio_anterior']): ?>
                                <div style="text-decoration: line-through; font-size: 0.75rem; color: rgba(255, 255, 255, 0.6);">$<?php echo number_format($producto['precio_anterior'], 0, ',', '.'); ?></div>
                                        <?php endif; ?>
                                    </td>
                            <td><?php echo $producto['stock']; ?></td>
                            <td>
                                <span class="badge badge-<?php echo $producto['estado'] === 'destacado' ? 'warning' : 'success'; ?>">
                                    <?php echo ucfirst($producto['estado']); ?>
                                </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Categorías con Productos</h3>
        </div>
        <div class="card-body">
                <div class="table-responsive">
                <table class="recent-table">
                        <thead>
                            <tr>
                                <th>Categoría</th>
                                <th>Productos</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($categorias_con_productos as $categoria): ?>
                                <tr>
                                    <td>
                                <div style="display: flex; align-items: center;">
                                    <div class="product-icon" style="background: <?php echo $categoria['color']; ?>">
                                        <i class="<?php echo $categoria['icono']; ?>"></i>
                                    </div>
                                            <div>
                                        <div style="font-weight: 500;"><?php echo $categoria['nombre']; ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                <div style="font-weight: 500;"><?php echo $categoria['productos']; ?></div>
                                    </td>
                                    <td>
                                <span class="badge badge-success"><?php echo ucfirst($categoria['estado']); ?></span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
        </div>
    </div>
</div>

<div class="quick-actions">
    <a href="productos.php" class="action-btn" style="--i: 1">
        <i class="fas fa-box"></i>
        <span>Gestionar Productos</span>
    </a>
    <a href="categorias.php" class="action-btn" style="--i: 2">
        <i class="fas fa-tags"></i>
        <span>Gestionar Categorías</span>
    </a>
    <a href="usuarios.php" class="action-btn" style="--i: 3">
        <i class="fas fa-users"></i>
        <span>Gestionar Usuarios</span>
    </a>
    <a href="pedidos.php" class="action-btn" style="--i: 4">
        <i class="fas fa-shopping-cart"></i>
        <span>Ver Pedidos</span>
    </a>
    <a href="configuracion.php" class="action-btn" style="--i: 5">
        <i class="fas fa-cog"></i>
        <span>Configuración</span>
    </a>
    <a href="../public/index.php" class="action-btn" style="--i: 6" target="_blank">
        <i class="fas fa-external-link-alt"></i>
        <span>Ver Tienda</span>
            </a>
        </div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const statNumbers = document.querySelectorAll('.stat-number');
        
        statNumbers.forEach(number => {
            const target = parseInt(number.textContent.replace(/[^\d]/g, ''));
            const duration = 2000;
            const increment = target / (duration / 16);
            let current = 0;
            
            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    current = target;
                    clearInterval(timer);
                }
                
                if (number.textContent.includes('$')) {
                    number.textContent = '$' + Math.floor(current).toLocaleString('es-CO');
                } else if (number.textContent.includes(',')) {
                    number.textContent = Math.floor(current).toLocaleString('es-CO');
                } else {
                    number.textContent = Math.floor(current);
                }
            }, 16);
        });

        const cards = document.querySelectorAll('.stat-card, .card');
        cards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-10px) scale(1.02)';
            });
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
            });
        });
    });
</script>

<?php include __DIR__ . '/layout_end.php'; ?>