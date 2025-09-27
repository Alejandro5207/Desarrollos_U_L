<?php
$page_title = 'Gestión de Usuarios';

// Datos de ejemplo para usuarios
$usuarios = [
    [
        'id' => 1,
        'nombre' => 'Mauricio',
        'apellido' => 'González',
        'email' => 'mauricio@tienda.com',
        'telefono' => '3001234567',
        'rol' => 'admin',
        'estado' => 'activo',
        'fecha_registro' => '2024-01-15',
        'avatar' => 'https://via.placeholder.com/60x60/007AFF/FFFFFF?text=MG'
    ],
    [
        'id' => 2,
        'nombre' => 'Ana',
        'apellido' => 'Rodríguez',
        'email' => 'ana@tienda.com',
        'telefono' => '3002345678',
        'rol' => 'cliente',
        'estado' => 'activo',
        'fecha_registro' => '2024-01-20',
        'avatar' => 'https://via.placeholder.com/60x60/34C759/FFFFFF?text=AR'
    ],
    [
        'id' => 3,
        'nombre' => 'Carlos',
        'apellido' => 'López',
        'email' => 'carlos@tienda.com',
        'telefono' => '3003456789',
        'rol' => 'cliente',
        'estado' => 'activo',
        'fecha_registro' => '2024-02-01',
        'avatar' => 'https://via.placeholder.com/60x60/FF9500/FFFFFF?text=CL'
    ],
    [
        'id' => 4,
        'nombre' => 'María',
        'apellido' => 'García',
        'email' => 'maria@tienda.com',
        'telefono' => '3004567890',
        'rol' => 'cliente',
        'estado' => 'inactivo',
        'fecha_registro' => '2024-02-10',
        'avatar' => 'https://via.placeholder.com/60x60/FF3B30/FFFFFF?text=MG'
    ],
    [
        'id' => 5,
        'nombre' => 'Pedro',
        'apellido' => 'Martínez',
        'email' => 'pedro@tienda.com',
        'telefono' => '3005678901',
        'rol' => 'cliente',
        'estado' => 'activo',
        'fecha_registro' => '2024-02-15',
        'avatar' => 'https://via.placeholder.com/60x60/5856D6/FFFFFF?text=PM'
    ],
    [
        'id' => 6,
        'nombre' => 'Laura',
        'apellido' => 'Fernández',
        'email' => 'laura@tienda.com',
        'telefono' => '3006789012',
        'rol' => 'cliente',
        'estado' => 'activo',
        'fecha_registro' => '2024-02-20',
        'avatar' => 'https://via.placeholder.com/60x60/8E8E93/FFFFFF?text=LF'
    ]
];

include __DIR__ . '/layout.php';
?>

<style>
    .users-header {
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

    .users-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .user-card {
        background: #ffffff;
        border-radius: 8px;
        padding: 1rem;
        border: 1px solid #e5e5e7;
        transition: all 0.2s ease;
        position: relative;
        overflow: hidden;
        animation: fadeInUp 0.6s ease-out calc(0.05s * var(--i)) both;
    }

    .user-card::before {
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

    .user-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .user-card:hover::before {
        opacity: 1;
    }

    .user-header {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 0.75rem;
        position: relative;
        z-index: 1;
    }

    .user-avatar {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #e5e5e7;
        transition: transform 0.2s ease;
    }

    .user-card:hover .user-avatar {
        transform: scale(1.1);
    }

    .user-info h3 {
        color: #1d1d1f;
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: 0.125rem;
    }

    .user-email {
        color: #6b7280;
        font-size: 0.75rem;
    }

    .user-details {
        margin-bottom: 0.75rem;
        position: relative;
        z-index: 1;
    }

    .detail-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.25rem;
        color: #374151;
        font-size: 0.75rem;
    }

    .detail-label {
        font-weight: 500;
    }

    .detail-value {
        font-weight: 600;
    }

    .user-badges {
        display: flex;
        gap: 0.25rem;
        margin-bottom: 0.75rem;
        position: relative;
        z-index: 1;
    }

    .user-actions {
        display: flex;
        gap: 0.25rem;
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
        <div class="stat-number"><?php echo count($usuarios); ?></div>
        <div class="stat-label">Total Usuarios</div>
    </div>
    <div class="stat-item" style="--i: 2">
        <div class="stat-number"><?php echo count(array_filter($usuarios, fn($u) => $u['estado'] === 'activo')); ?></div>
        <div class="stat-label">Usuarios Activos</div>
    </div>
    <div class="stat-item" style="--i: 3">
        <div class="stat-number"><?php echo count(array_filter($usuarios, fn($u) => $u['rol'] === 'admin')); ?></div>
        <div class="stat-label">Administradores</div>
    </div>
    <div class="stat-item" style="--i: 4">
        <div class="stat-number"><?php echo count(array_filter($usuarios, fn($u) => $u['rol'] === 'cliente')); ?></div>
        <div class="stat-label">Clientes</div>
    </div>
</div>

<div class="users-header">
    <h2 style="color: #ffffff; font-size: 1.5rem; font-weight: 700;">Gestión de Usuarios</h2>
    <a href="#" class="btn-primary">
        <i class="fas fa-plus"></i>
        Agregar Usuario
    </a>
</div>

<div class="users-grid">
    <?php foreach ($usuarios as $index => $usuario): ?>
    <div class="user-card" style="--i: <?php echo $index + 1; ?>">
        <div class="user-header">
            <img src="<?php echo $usuario['avatar']; ?>" alt="<?php echo $usuario['nombre']; ?>" class="user-avatar">
            <div class="user-info">
                <h3><?php echo $usuario['nombre'] . ' ' . $usuario['apellido']; ?></h3>
                <div class="user-email"><?php echo $usuario['email']; ?></div>
            </div>
        </div>

        <div class="user-details">
            <div class="detail-row">
                <span class="detail-label">Teléfono:</span>
                <span class="detail-value"><?php echo $usuario['telefono']; ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Registro:</span>
                <span class="detail-value"><?php echo date('d/m/Y', strtotime($usuario['fecha_registro'])); ?></span>
            </div>
        </div>

        <div class="user-badges">
            <span class="badge badge-<?php echo $usuario['estado'] === 'activo' ? 'success' : 'error'; ?>">
                <?php echo ucfirst($usuario['estado']); ?>
            </span>
            <span class="badge badge-info">
                <?php echo ucfirst($usuario['rol']); ?>
            </span>
        </div>

        <div class="user-actions">
            <a href="#" class="btn-action btn-edit">
                <i class="fas fa-edit"></i> Editar
            </a>
            <a href="#" class="btn-action btn-delete" onclick="return confirmDelete('¿Estás seguro de eliminar este usuario?')">
                <i class="fas fa-trash"></i> Eliminar
            </a>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const cards = document.querySelectorAll('.user-card, .stat-item');
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