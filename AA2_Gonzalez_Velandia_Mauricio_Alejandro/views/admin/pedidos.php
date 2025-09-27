<?php
$page_title = 'Gestión de Pedidos';

// Datos de ejemplo para pedidos
$pedidos = [
    [
        'id' => 1,
        'cliente' => 'Ana Rodríguez',
        'email' => 'ana@tienda.com',
        'telefono' => '3002345678',
        'fecha' => '2024-02-20',
        'estado' => 'pendiente',
        'total' => 4200000,
        'productos' => 2,
        'metodo_pago' => 'tarjeta'
    ],
    [
        'id' => 2,
        'cliente' => 'Carlos López',
        'email' => 'carlos@tienda.com',
        'telefono' => '3003456789',
        'fecha' => '2024-02-19',
        'estado' => 'procesando',
        'total' => 750000,
        'productos' => 1,
        'metodo_pago' => 'transferencia'
    ],
    [
        'id' => 3,
        'cliente' => 'María García',
        'email' => 'maria@tienda.com',
        'telefono' => '3004567890',
        'fecha' => '2024-02-18',
        'estado' => 'enviado',
        'total' => 1800000,
        'productos' => 1,
        'metodo_pago' => 'efectivo'
    ],
    [
        'id' => 4,
        'cliente' => 'Pedro Martínez',
        'email' => 'pedro@tienda.com',
        'telefono' => '3005678901',
        'fecha' => '2024-02-17',
        'estado' => 'entregado',
        'total' => 3200000,
        'productos' => 1,
        'metodo_pago' => 'tarjeta'
    ],
    [
        'id' => 5,
        'cliente' => 'Laura Fernández',
        'email' => 'laura@tienda.com',
        'telefono' => '3006789012',
        'fecha' => '2024-02-16',
        'estado' => 'cancelado',
        'total' => 5500000,
        'productos' => 1,
        'metodo_pago' => 'tarjeta'
    ],
    [
        'id' => 6,
        'cliente' => 'Diego Ramírez',
        'email' => 'diego@tienda.com',
        'telefono' => '3007890123',
        'fecha' => '2024-02-15',
        'estado' => 'pendiente',
        'total' => 1200000,
        'productos' => 3,
        'metodo_pago' => 'transferencia'
    ]
];

include __DIR__ . '/layout.php';
?>

<style>
    .orders-header {
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

    .orders-table {
        background: #ffffff;
        border-radius: 8px;
        border: 1px solid #e5e5e7;
        overflow: hidden;
        animation: fadeInUp 0.6s ease-out;
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

    .order-id {
        font-weight: 700;
        color: #007AFF;
        font-size: 0.75rem;
    }

    .client-info {
        display: flex;
        flex-direction: column;
        gap: 0.125rem;
    }

    .client-name {
        font-weight: 600;
        color: #1d1d1f;
        font-size: 0.75rem;
    }

    .client-contact {
        font-size: 0.625rem;
        color: #6b7280;
    }

    .order-date {
        font-weight: 500;
        font-size: 0.75rem;
    }

    .order-total {
        font-weight: 700;
        color: #34C759;
        font-size: 0.75rem;
    }

    .order-products {
        text-align: center;
        font-weight: 600;
        font-size: 0.75rem;
    }

    .payment-method {
        display: flex;
        align-items: center;
        gap: 0.25rem;
        font-size: 0.625rem;
    }

    .payment-icon {
        width: 14px;
        height: 14px;
        border-radius: 2px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.5rem;
        color: white;
    }

    .order-actions {
        display: flex;
        gap: 0.25rem;
    }

    .btn-action {
        padding: 0.25rem 0.375rem;
        border-radius: 4px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.2s ease;
        font-size: 0.625rem;
        border: none;
        cursor: pointer;
    }

    .btn-view {
        background: rgba(0, 122, 255, 0.2);
        color: #007AFF;
        border: 1px solid rgba(0, 122, 255, 0.4);
    }

    .btn-view:hover {
        background: rgba(0, 122, 255, 0.3);
        transform: translateY(-2px);
    }

    .btn-edit {
        background: rgba(255, 149, 0, 0.2);
        color: #FF9500;
        border: 1px solid rgba(255, 149, 0, 0.4);
    }

    .btn-edit:hover {
        background: rgba(255, 149, 0, 0.3);
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
        <div class="stat-number"><?php echo count($pedidos); ?></div>
        <div class="stat-label">Total Pedidos</div>
    </div>
    <div class="stat-item" style="--i: 2">
        <div class="stat-number"><?php echo count(array_filter($pedidos, fn($p) => $p['estado'] === 'pendiente')); ?></div>
        <div class="stat-label">Pendientes</div>
    </div>
    <div class="stat-item" style="--i: 3">
        <div class="stat-number"><?php echo count(array_filter($pedidos, fn($p) => $p['estado'] === 'entregado')); ?></div>
        <div class="stat-label">Entregados</div>
    </div>
    <div class="stat-item" style="--i: 4">
        <div class="stat-number">$<?php echo number_format(array_sum(array_column($pedidos, 'total')), 0, ',', '.'); ?></div>
        <div class="stat-label">Total Ventas</div>
    </div>
</div>

<div class="orders-header">
    <h2 style="color: #ffffff; font-size: 1.5rem; font-weight: 700;">Gestión de Pedidos</h2>
    <a href="#" class="btn-primary">
        <i class="fas fa-plus"></i>
        Nuevo Pedido
    </a>
</div>

<div class="orders-table">
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Cliente</th>
                <th>Fecha</th>
                <th>Estado</th>
                <th>Total</th>
                <th>Productos</th>
                <th>Pago</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($pedidos as $pedido): ?>
            <tr>
                <td>
                    <span class="order-id">#<?php echo str_pad($pedido['id'], 4, '0', STR_PAD_LEFT); ?></span>
                </td>
                <td>
                    <div class="client-info">
                        <span class="client-name"><?php echo $pedido['cliente']; ?></span>
                        <span class="client-contact"><?php echo $pedido['email']; ?></span>
                        <span class="client-contact"><?php echo $pedido['telefono']; ?></span>
                    </div>
                </td>
                <td>
                    <span class="order-date"><?php echo date('d/m/Y', strtotime($pedido['fecha'])); ?></span>
                </td>
                <td>
                    <span class="badge badge-<?php 
                        echo match($pedido['estado']) {
                            'pendiente' => 'warning',
                            'procesando' => 'info',
                            'enviado' => 'primary',
                            'entregado' => 'success',
                            'cancelado' => 'error',
                            default => 'secondary'
                        };
                    ?>">
                        <?php echo ucfirst($pedido['estado']); ?>
                    </span>
                </td>
                <td>
                    <span class="order-total">$<?php echo number_format($pedido['total'], 0, ',', '.'); ?></span>
                </td>
                <td>
                    <span class="order-products"><?php echo $pedido['productos']; ?></span>
                </td>
                <td>
                    <div class="payment-method">
                        <div class="payment-icon" style="background: <?php 
                            echo match($pedido['metodo_pago']) {
                                'tarjeta' => '#007AFF',
                                'transferencia' => '#34C759',
                                'efectivo' => '#FF9500',
                                default => '#8E8E93'
                            };
                        ?>">
                            <i class="fas fa-<?php 
                                echo match($pedido['metodo_pago']) {
                                    'tarjeta' => 'credit-card',
                                    'transferencia' => 'university',
                                    'efectivo' => 'money-bill',
                                    default => 'question'
                                };
                            ?>"></i>
                        </div>
                        <span><?php echo ucfirst($pedido['metodo_pago']); ?></span>
                    </div>
                </td>
                <td>
                    <div class="order-actions">
                        <a href="#" class="btn-action btn-view">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="#" class="btn-action btn-edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a href="#" class="btn-action btn-delete" onclick="return confirmDelete('¿Estás seguro de eliminar este pedido?')">
                            <i class="fas fa-trash"></i>
                        </a>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const statItems = document.querySelectorAll('.stat-item');
        statItems.forEach(item => {
            item.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-5px)';
            });
            item.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });

        const tableRows = document.querySelectorAll('.table tbody tr');
        tableRows.forEach(row => {
            row.addEventListener('mouseenter', function() {
                this.style.transform = 'scale(1.01)';
            });
            row.addEventListener('mouseleave', function() {
                this.style.transform = 'scale(1)';
            });
        });
    });
</script>

<?php include __DIR__ . '/layout_end.php'; ?>