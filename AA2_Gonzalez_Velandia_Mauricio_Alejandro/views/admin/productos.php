<?php
$page_title = 'Gestión de Productos';

// Datos de ejemplo para productos
$productos = [
    [
        'id' => 1,
        'nombre' => 'iPhone 15 Pro',
        'precio' => 4200000,
        'stock' => 25,
        'categoria' => 'Electrónicos',
        'estado' => 'activo',
        'destacado' => true,
        'imagen_principal' => 'https://via.placeholder.com/100x100/007AFF/FFFFFF?text=iPhone'
    ],
    [
        'id' => 2,
        'nombre' => 'MacBook Air M2',
        'precio' => 5500000,
        'stock' => 15,
        'categoria' => 'Computadoras',
        'estado' => 'activo',
        'destacado' => false,
        'imagen_principal' => 'https://via.placeholder.com/100x100/FF9500/FFFFFF?text=MacBook'
    ],
    [
        'id' => 3,
        'nombre' => 'AirPods Pro',
        'precio' => 750000,
        'stock' => 50,
        'categoria' => 'Audio',
        'estado' => 'activo',
        'destacado' => true,
        'imagen_principal' => 'https://via.placeholder.com/100x100/34C759/FFFFFF?text=AirPods'
    ],
    [
        'id' => 4,
        'nombre' => 'iPad Pro',
        'precio' => 3200000,
        'stock' => 30,
        'categoria' => 'Tablets',
        'estado' => 'activo',
        'destacado' => false,
        'imagen_principal' => 'https://via.placeholder.com/100x100/8E8E93/FFFFFF?text=iPad'
    ],
    [
        'id' => 5,
        'nombre' => 'Apple Watch',
        'precio' => 1800000,
        'stock' => 40,
        'categoria' => 'Wearables',
        'estado' => 'activo',
        'destacado' => true,
        'imagen_principal' => 'https://via.placeholder.com/100x100/FF3B30/FFFFFF?text=Watch'
    ]
];

include __DIR__ . '/layout.php';
?>

<style>
    .products-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
        padding: 1rem;
        background: #ffffff;
        border-radius: 8px;
        border: 1px solid #e5e5e7;
        animation: fadeInDown 0.6s ease-out;
    }

    .products-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .product-card {
        background: #ffffff;
        border-radius: 8px;
        padding: 1rem;
        border: 1px solid #e5e5e7;
        transition: all 0.2s ease;
        position: relative;
        overflow: hidden;
        animation: fadeInUp 0.6s ease-out calc(0.05s * var(--i)) both;
    }

    .product-card::before {
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

    .product-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .product-card:hover::before {
        opacity: 1;
    }

    .product-image {
        width: 60px;
        height: 60px;
        border-radius: 8px;
        margin-bottom: 0.75rem;
        object-fit: cover;
        transition: transform 0.2s ease;
    }

    .product-card:hover .product-image {
        transform: scale(1.1) rotate(5deg);
    }

    .product-name {
        font-size: 1rem;
        font-weight: 600;
        color: #1d1d1f;
        margin-bottom: 0.25rem;
    }

    .product-price {
        font-size: 1.125rem;
        font-weight: 700;
        color: #34C759;
        margin-bottom: 0.25rem;
    }

    .product-stock {
        color: #6b7280;
        margin-bottom: 0.75rem;
        font-size: 0.875rem;
    }

    .product-badges {
        display: flex;
        gap: 0.25rem;
        margin-bottom: 0.75rem;
    }

    .product-actions {
        display: flex;
        gap: 0.25rem;
        margin-top: 0.75rem;
    }

    .btn-action {
        flex: 1;
        padding: 0.375rem;
        border-radius: 6px;
        text-decoration: none;
        text-align: center;
        font-weight: 600;
        transition: all 0.2s ease;
        font-size: 0.75rem;
    }

    .btn-edit {
        background: rgba(0, 122, 255, 0.2);
        color: #007AFF;
        border: 1px solid rgba(0, 122, 255, 0.4);
    }

    .btn-edit:hover {
        background: rgba(0, 122, 255, 0.3);
        transform: translateY(-2px);
    }

    .btn-delete {
        background: rgba(255, 59, 48, 0.2);
        color: #FF3B30;
        border: 1px solid rgba(255, 59, 48, 0.4);
    }

    .btn-delete:hover {
        background: rgba(255, 59, 48, 0.3);
        transform: translateY(-2px);
    }

    .stats-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 0.75rem;
        margin-bottom: 1rem;
    }

    .stat-item {
        background: #ffffff;
        padding: 1rem;
        border-radius: 8px;
        border: 1px solid #e5e5e7;
        text-align: center;
        transition: all 0.2s ease;
        animation: fadeInUp 0.6s ease-out calc(0.05s * var(--i)) both;
    }

    .stat-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .stat-number {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1d1d1f;
        margin-bottom: 0.25rem;
    }

    .stat-label {
        color: #6b7280;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-size: 0.75rem;
    }
</style>

<div class="stats-row">
    <div class="stat-item" style="--i: 1">
        <div class="stat-number"><?php echo count($productos); ?></div>
        <div class="stat-label">Total Productos</div>
    </div>
    <div class="stat-item" style="--i: 2">
        <div class="stat-number"><?php echo count(array_filter($productos, fn($p) => $p['estado'] === 'activo')); ?></div>
        <div class="stat-label">Productos Activos</div>
    </div>
    <div class="stat-item" style="--i: 3">
        <div class="stat-number"><?php echo count(array_filter($productos, fn($p) => $p['destacado'])); ?></div>
        <div class="stat-label">Productos Destacados</div>
    </div>
    <div class="stat-item" style="--i: 4">
        <div class="stat-number"><?php echo number_format(array_sum(array_column($productos, 'stock')), 0, ',', '.'); ?></div>
        <div class="stat-label">Stock Total</div>
    </div>
</div>

<div class="products-header">
    <h2 style="color: #ffffff; font-size: 1.5rem; font-weight: 700;">Gestión de Productos</h2>
    <a href="#" class="btn-primary">
        <i class="fas fa-plus"></i>
        Agregar Producto
    </a>
</div>

<div class="products-grid">
    <?php foreach ($productos as $index => $producto): ?>
    <div class="product-card" style="--i: <?php echo $index + 1; ?>">
        <img src="<?php echo $producto['imagen_principal']; ?>" alt="<?php echo $producto['nombre']; ?>" class="product-image">
        <h3 class="product-name"><?php echo $producto['nombre']; ?></h3>
        <div class="product-price">$<?php echo number_format($producto['precio'], 0, ',', '.'); ?></div>
        <div class="product-stock">Stock: <?php echo $producto['stock']; ?> unidades</div>
        
        <div class="product-badges">
            <span class="badge badge-success"><?php echo ucfirst($producto['estado']); ?></span>
            <?php if ($producto['destacado']): ?>
            <span class="badge badge-warning">Destacado</span>
            <?php endif; ?>
        </div>

        <div class="product-actions">
            <a href="#" class="btn-action btn-edit">
                <i class="fas fa-edit"></i> Editar
            </a>
            <a href="#" class="btn-action btn-delete" onclick="return confirmDelete('¿Estás seguro de eliminar este producto?')">
                <i class="fas fa-trash"></i> Eliminar
            </a>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const cards = document.querySelectorAll('.product-card, .stat-item');
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