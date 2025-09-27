<?php
$page_title = 'Gestión de Categorías';

// Datos de ejemplo para categorías
$categorias = [
    [
        'id' => 1,
        'nombre' => 'Electrónicos',
        'descripcion' => 'Dispositivos electrónicos y tecnología',
        'productos_count' => 12,
        'estado' => 'activo',
        'icono' => 'fas fa-mobile-alt',
        'color' => '#007AFF'
    ],
    [
        'id' => 2,
        'nombre' => 'Computadoras',
        'descripcion' => 'Laptops, desktops y accesorios',
        'productos_count' => 8,
        'estado' => 'activo',
        'icono' => 'fas fa-laptop',
        'color' => '#FF9500'
    ],
    [
        'id' => 3,
        'nombre' => 'Audio',
        'descripcion' => 'Auriculares, altavoces y audio',
        'productos_count' => 15,
        'estado' => 'activo',
        'icono' => 'fas fa-headphones',
        'color' => '#34C759'
    ],
    [
        'id' => 4,
        'nombre' => 'Accesorios',
        'descripcion' => 'Accesorios y complementos',
        'productos_count' => 6,
        'estado' => 'activo',
        'icono' => 'fas fa-keyboard',
        'color' => '#8E8E93'
    ],
    [
        'id' => 5,
        'nombre' => 'Gaming',
        'descripcion' => 'Productos para gaming y entretenimiento',
        'productos_count' => 4,
        'estado' => 'activo',
        'icono' => 'fas fa-gamepad',
        'color' => '#FF3B30'
    ],
    [
        'id' => 6,
        'nombre' => 'Tablets',
        'descripcion' => 'Tablets y dispositivos móviles',
        'productos_count' => 7,
        'estado' => 'activo',
        'icono' => 'fas fa-tablet-alt',
        'color' => '#5856D6'
    ]
];

include __DIR__ . '/layout.php';
?>

<style>
    .categories-header {
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

    .categories-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .category-card {
        background: #ffffff;
        border-radius: 8px;
        padding: 1rem;
        border: 1px solid #e5e5e7;
        transition: all 0.2s ease;
        position: relative;
        overflow: hidden;
        animation: fadeInUp 0.6s ease-out calc(0.05s * var(--i)) both;
        cursor: pointer;
    }

    .category-card::before {
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

    .category-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .category-card:hover::before {
        opacity: 1;
    }

    .category-icon {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.125rem;
        color: white;
        margin-bottom: 0.75rem;
        transition: all 0.2s ease;
        position: relative;
        z-index: 1;
    }

    .category-card:hover .category-icon {
        transform: scale(1.1) rotate(10deg);
    }

    .category-name {
        font-size: 1.125rem;
        font-weight: 600;
        color: #1d1d1f;
        margin-bottom: 0.25rem;
        position: relative;
        z-index: 1;
    }

    .category-description {
        color: #6b7280;
        margin-bottom: 0.75rem;
        font-size: 0.75rem;
        position: relative;
        z-index: 1;
    }

    .category-stats {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.75rem;
        position: relative;
        z-index: 1;
    }

    .product-count {
        font-size: 1rem;
        font-weight: 700;
        color: #34C759;
    }

    .category-actions {
        display: flex;
        gap: 0.25rem;
        margin-top: 0.75rem;
        position: relative;
        z-index: 1;
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
        <div class="stat-number"><?php echo count($categorias); ?></div>
        <div class="stat-label">Total Categorías</div>
    </div>
    <div class="stat-item" style="--i: 2">
        <div class="stat-number"><?php echo count(array_filter($categorias, fn($c) => $c['estado'] === 'activo')); ?></div>
        <div class="stat-label">Categorías Activas</div>
    </div>
    <div class="stat-item" style="--i: 3">
        <div class="stat-number"><?php echo array_sum(array_column($categorias, 'productos_count')); ?></div>
        <div class="stat-label">Total Productos</div>
    </div>
    <div class="stat-item" style="--i: 4">
        <div class="stat-number"><?php echo number_format(array_sum(array_column($categorias, 'productos_count')) / count($categorias), 1); ?></div>
        <div class="stat-label">Promedio por Categoría</div>
    </div>
</div>

<div class="categories-header">
    <h2 style="color: #ffffff; font-size: 1.5rem; font-weight: 700;">Gestión de Categorías</h2>
    <a href="#" class="btn-primary">
        <i class="fas fa-plus"></i>
        Agregar Categoría
    </a>
</div>

<div class="categories-grid">
    <?php foreach ($categorias as $index => $categoria): ?>
    <div class="category-card" style="--i: <?php echo $index + 1; ?>">
        <div class="category-icon" style="background: <?php echo $categoria['color']; ?>">
            <i class="<?php echo $categoria['icono']; ?>"></i>
        </div>
        <h3 class="category-name"><?php echo $categoria['nombre']; ?></h3>
        <p class="category-description"><?php echo $categoria['descripcion']; ?></p>
        
        <div class="category-stats">
            <div class="product-count"><?php echo $categoria['productos_count']; ?> productos</div>
            <span class="badge badge-success"><?php echo ucfirst($categoria['estado']); ?></span>
        </div>

        <div class="category-actions">
            <a href="#" class="btn-action btn-edit">
                <i class="fas fa-edit"></i> Editar
            </a>
            <a href="#" class="btn-action btn-delete" onclick="return confirmDelete('¿Estás seguro de eliminar esta categoría?')">
                <i class="fas fa-trash"></i> Eliminar
            </a>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const cards = document.querySelectorAll('.category-card, .stat-item');
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