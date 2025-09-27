<?php
require_once '../includes/auth.php';
require_once '../config/database.php';
require_once '../models/Pedido.php';
require_once '../models/Usuario.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$database = new Database();
$pdo = $database->getConnection();

$pedido = new Pedido($pdo);
$usuario = new Usuario($pdo);

$action = $_GET['action'] ?? 'list';
$id = $_GET['id'] ?? null;

switch ($action) {
    case 'create':
        if ($_POST) {
            $data = [
                'usuario_id' => $_POST['usuario_id'],
                'total' => $_POST['total'],
                'estado' => $_POST['estado'],
                'direccion_envio' => $_POST['direccion_envio'],
                'telefono_contacto' => $_POST['telefono_contacto'],
                'notas' => $_POST['notas'],
                'metodo_pago' => $_POST['metodo_pago']
            ];
            
            if ($pedido->create($data)) {
                $_SESSION['success'] = 'Pedido creado exitosamente';
                header('Location: pedidos.php');
                exit;
            } else {
                $_SESSION['error'] = 'Error al crear el pedido';
            }
        }
        $usuarios = $usuario->getAll();
        break;
        
    case 'edit':
        if ($_POST) {
            $data = [
                'usuario_id' => $_POST['usuario_id'],
                'total' => $_POST['total'],
                'estado' => $_POST['estado'],
                'direccion_envio' => $_POST['direccion_envio'],
                'telefono_contacto' => $_POST['telefono_contacto'],
                'notas' => $_POST['notas'],
                'metodo_pago' => $_POST['metodo_pago']
            ];
            
            if ($pedido->update($id, $data)) {
                $_SESSION['success'] = 'Pedido actualizado exitosamente';
                header('Location: pedidos.php');
                exit;
            } else {
                $_SESSION['error'] = 'Error al actualizar el pedido';
            }
        }
        $pedido_data = $pedido->getById($id);
        $usuarios = $usuario->getAll();
        break;
        
    case 'delete':
        if ($pedido->delete($id)) {
            $_SESSION['success'] = 'Pedido eliminado exitosamente';
        } else {
            $_SESSION['error'] = 'Error al eliminar el pedido';
        }
        header('Location: pedidos.php');
        exit;
        
    case 'change_status':
        $new_status = $_GET['status'] ?? '';
        $valid_statuses = ['pendiente', 'procesando', 'enviado', 'entregado', 'cancelado'];
        
        if (in_array($new_status, $valid_statuses)) {
            if ($pedido->updateEstado($id, $new_status)) {
                $_SESSION['success'] = 'Estado del pedido actualizado a: ' . ucfirst($new_status);
            } else {
                $_SESSION['error'] = 'Error al actualizar el estado del pedido';
            }
        } else {
            $_SESSION['error'] = 'Estado no válido';
        }
        header('Location: pedidos.php');
        exit;
        
    default:
        $pedidos = $pedido->getAll();
        $pedidos_stats = $pedido->getStats();
        break;
}

include '../views/admin/layout.php';
?>

<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
        padding: 1rem;
        background: #ffffff;
        border-radius: 8px;
        border: 1px solid #e5e5e7;
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
    }

    .stat-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .stat-number {
        font-size: 1.5rem;
        font-weight: 700;
        color: #007AFF;
        margin-bottom: 0.25rem;
    }

    .stat-label {
        font-size: 0.75rem;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .orders-table {
        background: #ffffff;
        border-radius: 8px;
        border: 1px solid #e5e5e7;
        overflow: hidden;
        margin-bottom: 1rem;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
    }

    .table th {
        background: #f8f9fa;
        color: #374151;
        font-weight: 600;
        padding: 0.75rem;
        text-align: left;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 1px solid #e5e5e7;
    }

    .table td {
        padding: 0.75rem;
        border-bottom: 1px solid #f3f4f6;
        color: #374151;
        transition: all 0.2s ease;
    }

    .table tr:hover {
        background: #f8f9fa;
    }

    .table tr:hover td {
        color: #1d1d1f;
    }

    .order-id {
        font-size: 0.75rem;
        font-weight: 600;
        color: #007AFF;
    }

    .client-info {
        display: flex;
        flex-direction: column;
        gap: 0.125rem;
    }

    .client-name {
        font-size: 0.75rem;
        font-weight: 600;
        color: #1d1d1f;
    }

    .client-contact {
        font-size: 0.625rem;
        color: #6b7280;
    }

    .order-date {
        font-size: 0.75rem;
        color: #6b7280;
    }

    .order-total {
        font-size: 0.75rem;
        font-weight: 600;
        color: #34C759;
    }

    .order-products {
        font-size: 0.75rem;
        color: #6b7280;
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

    .payment-cash { background: #34C759; }
    .payment-card { background: #007AFF; }
    .payment-paypal { background: #FF9500; }
    .payment-transfer { background: #5856D6; }

    .order-status {
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
        border: 1px solid #fde68a;
    }

    .status-procesando {
        background: #dbeafe;
        color: #1e40af;
        border: 1px solid #bfdbfe;
    }

    .status-enviado {
        background: #e0e7ff;
        color: #3730a3;
        border: 1px solid #c7d2fe;
    }

    .status-entregado {
        background: #dcfce7;
        color: #166534;
        border: 1px solid #bbf7d0;
    }

    .status-cancelado {
        background: #fee2e2;
        color: #991b1b;
        border: 1px solid #fecaca;
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
        text-align: center;
    }

    .btn-edit {
        background: #dbeafe;
        color: #1e40af;
        border: 1px solid #bfdbfe;
    }

    .btn-delete {
        background: #fee2e2;
        color: #991b1b;
        border: 1px solid #fecaca;
    }

    .btn-process {
        background: #dbeafe;
        color: #1e40af;
        border: 1px solid #bfdbfe;
    }

    .btn-ship {
        background: #fef3c7;
        color: #92400e;
        border: 1px solid #fde68a;
    }

    .btn-deliver {
        background: #dcfce7;
        color: #166534;
        border: 1px solid #bbf7d0;
    }

    .btn-cancel {
        background: #fee2e2;
        color: #991b1b;
        border: 1px solid #fecaca;
    }

    .btn-create {
        background: #007AFF;
        color: #ffffff;
        padding: 0.5rem 1rem;
        border-radius: 6px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.2s ease;
        font-size: 0.875rem;
    }

    .btn-create:hover {
        background: #0056CC;
        transform: translateY(-1px);
    }

    .form-container {
        background: #ffffff;
        border-radius: 8px;
        border: 1px solid #e5e5e7;
        padding: 1.5rem;
        margin-bottom: 1rem;
    }

    .form-group {
        margin-bottom: 1rem;
    }

    .form-label {
        display: block;
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.375rem;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .form-input {
        width: 100%;
        padding: 0.5rem 0.75rem;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        font-size: 0.875rem;
        background: #ffffff;
        color: #374151;
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

    .form-select {
        appearance: none;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
        background-position: right 0.5rem center;
        background-repeat: no-repeat;
        background-size: 1.5em 1.5em;
        padding-right: 2.5rem;
    }

    .form-actions {
        display: flex;
        gap: 0.75rem;
        margin-top: 1rem;
    }

    .btn-save {
        background: #007AFF;
        color: #ffffff;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 6px;
        font-weight: 600;
        transition: all 0.2s ease;
        cursor: pointer;
        font-size: 0.875rem;
    }

    .btn-save:hover {
        background: #0056CC;
        transform: translateY(-1px);
    }

    .btn-cancel {
        background: #f3f4f6;
        color: #374151;
        border: 1px solid #d1d5db;
        padding: 0.5rem 1rem;
        border-radius: 6px;
        font-weight: 600;
        transition: all 0.2s ease;
        text-decoration: none;
        font-size: 0.875rem;
    }

    .btn-cancel:hover {
        background: #e5e7eb;
        transform: translateY(-1px);
    }

    .alert {
        padding: 0.75rem 1rem;
        border-radius: 6px;
        margin-bottom: 1rem;
        font-size: 0.875rem;
        font-weight: 500;
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
</style>

<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success">
        <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
    </div>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-error">
        <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
    </div>
<?php endif; ?>

<?php if ($action === 'create' || $action === 'edit'): ?>
    <div class="form-container">
        <h2><?php echo $action === 'create' ? 'Crear Pedido' : 'Editar Pedido'; ?></h2>
        
        <form method="POST">
            <div class="form-group">
                <label class="form-label" for="usuario_id">Cliente</label>
                <select class="form-input form-select" id="usuario_id" name="usuario_id" required>
                    <option value="">Seleccionar cliente</option>
                    <?php foreach ($usuarios as $user): ?>
                        <option value="<?php echo $user['id']; ?>" 
                                <?php echo (isset($pedido_data['usuario_id']) && $pedido_data['usuario_id'] == $user['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($user['nombre'] . ' ' . $user['apellido']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label class="form-label" for="total">Total (COP)</label>
                <input type="number" class="form-input" id="total" name="total" 
                       value="<?php echo $pedido_data['total'] ?? ''; ?>" required>
            </div>
            
            <div class="form-group">
                <label class="form-label" for="estado">Estado</label>
                <select class="form-input form-select" id="estado" name="estado" required>
                    <option value="">Seleccionar estado</option>
                    <option value="pendiente" <?php echo (isset($pedido_data['estado']) && $pedido_data['estado'] === 'pendiente') ? 'selected' : ''; ?>>Pendiente</option>
                    <option value="procesando" <?php echo (isset($pedido_data['estado']) && $pedido_data['estado'] === 'procesando') ? 'selected' : ''; ?>>Procesando</option>
                    <option value="enviado" <?php echo (isset($pedido_data['estado']) && $pedido_data['estado'] === 'enviado') ? 'selected' : ''; ?>>Enviado</option>
                    <option value="entregado" <?php echo (isset($pedido_data['estado']) && $pedido_data['estado'] === 'entregado') ? 'selected' : ''; ?>>Entregado</option>
                    <option value="cancelado" <?php echo (isset($pedido_data['estado']) && $pedido_data['estado'] === 'cancelado') ? 'selected' : ''; ?>>Cancelado</option>
                </select>
            </div>
            
            <div class="form-group">
                <label class="form-label" for="direccion_envio">Dirección de Envío</label>
                <textarea class="form-input form-textarea" id="direccion_envio" name="direccion_envio" required><?php echo $pedido_data['direccion_envio'] ?? ''; ?></textarea>
            </div>
            
            <div class="form-group">
                <label class="form-label" for="telefono_contacto">Teléfono de Contacto</label>
                <input type="tel" class="form-input" id="telefono_contacto" name="telefono_contacto" 
                       value="<?php echo $pedido_data['telefono_contacto'] ?? ''; ?>" required>
            </div>
            
            <div class="form-group">
                <label class="form-label" for="metodo_pago">Método de Pago</label>
                <select class="form-input form-select" id="metodo_pago" name="metodo_pago" required>
                    <option value="">Seleccionar método</option>
                    <option value="Efectivo" <?php echo (isset($pedido_data['metodo_pago']) && $pedido_data['metodo_pago'] === 'Efectivo') ? 'selected' : ''; ?>>Efectivo</option>
                    <option value="Tarjeta de Crédito" <?php echo (isset($pedido_data['metodo_pago']) && $pedido_data['metodo_pago'] === 'Tarjeta de Crédito') ? 'selected' : ''; ?>>Tarjeta de Crédito</option>
                    <option value="PayPal" <?php echo (isset($pedido_data['metodo_pago']) && $pedido_data['metodo_pago'] === 'PayPal') ? 'selected' : ''; ?>>PayPal</option>
                    <option value="Transferencia Bancaria" <?php echo (isset($pedido_data['metodo_pago']) && $pedido_data['metodo_pago'] === 'Transferencia Bancaria') ? 'selected' : ''; ?>>Transferencia Bancaria</option>
                </select>
            </div>
            
            <div class="form-group">
                <label class="form-label" for="notas">Notas</label>
                <textarea class="form-input form-textarea" id="notas" name="notas"><?php echo $pedido_data['notas'] ?? ''; ?></textarea>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn-save">
                    <i class="fas fa-save"></i> Guardar
                </button>
                <a href="pedidos.php" class="btn-cancel">
                    <i class="fas fa-times"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
<?php else: ?>
    <div class="page-header">
        <h2>Gestión de Pedidos</h2>
        <a href="pedidos.php?action=create" class="btn-create">
            <i class="fas fa-plus"></i> Nuevo Pedido
        </a>
    </div>

    <div class="stats-row">
        <div class="stat-item">
            <div class="stat-number"><?php echo $pedidos_stats['total_pedidos']; ?></div>
            <div class="stat-label">Total Pedidos</div>
        </div>
        <div class="stat-item">
            <div class="stat-number"><?php echo $pedidos_stats['pedidos_pendientes']; ?></div>
            <div class="stat-label">Pendientes</div>
        </div>
        <div class="stat-item">
            <div class="stat-number"><?php echo $pedidos_stats['pedidos_entregados']; ?></div>
            <div class="stat-label">Entregados</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">$<?php echo number_format($pedidos_stats['ingresos_totales'], 0, ',', '.'); ?></div>
            <div class="stat-label">Ingresos Totales</div>
        </div>
    </div>

    <div class="orders-table">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Cliente</th>
                    <th>Fecha</th>
                    <th>Total</th>
                    <th>Estado</th>
                    <th>Pago</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pedidos as $ped): ?>
                    <tr>
                        <td>
                            <span class="order-id">#<?php echo $ped['id']; ?></span>
                        </td>
                        <td>
                            <div class="client-info">
                                <div class="client-name"><?php echo htmlspecialchars($ped['usuario_nombre'] . ' ' . $ped['usuario_apellido']); ?></div>
                                <div class="client-contact"><?php echo htmlspecialchars($ped['usuario_email']); ?></div>
                            </div>
                        </td>
                        <td>
                            <div class="order-date"><?php echo date('d/m/Y', strtotime($ped['fecha_pedido'])); ?></div>
                        </td>
                        <td>
                            <div class="order-total">$<?php echo number_format($ped['total'], 0, ',', '.'); ?></div>
                        </td>
                        <td>
                            <span class="order-status status-<?php echo $ped['estado']; ?>">
                                <?php echo ucfirst($ped['estado']); ?>
                            </span>
                        </td>
                        <td>
                            <div class="payment-method">
                                <div class="payment-icon payment-<?php echo strtolower(str_replace(' ', '-', $ped['metodo_pago'])); ?>">
                                    <i class="fas fa-<?php echo $ped['metodo_pago'] === 'Efectivo' ? 'money-bill' : ($ped['metodo_pago'] === 'Tarjeta de Crédito' ? 'credit-card' : ($ped['metodo_pago'] === 'PayPal' ? 'paypal' : 'university')); ?>"></i>
                                </div>
                                <?php echo htmlspecialchars($ped['metodo_pago']); ?>
                            </div>
                        </td>
                        <td>
                            <div class="order-actions">
                                <a href="pedidos.php?action=edit&id=<?php echo $ped['id']; ?>" 
                                   class="btn-action btn-edit" title="Editar pedido">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <?php if ($ped['estado'] !== 'entregado' && $ped['estado'] !== 'cancelado'): ?>
                                    <?php if ($ped['estado'] === 'pendiente'): ?>
                                        <a href="pedidos.php?action=change_status&id=<?php echo $ped['id']; ?>&status=procesando" 
                                           class="btn-action btn-process" title="Marcar como Procesando">
                                            <i class="fas fa-cog"></i>
                                        </a>
                                    <?php elseif ($ped['estado'] === 'procesando'): ?>
                                        <a href="pedidos.php?action=change_status&id=<?php echo $ped['id']; ?>&status=enviado" 
                                           class="btn-action btn-ship" title="Marcar como Enviado">
                                            <i class="fas fa-truck"></i>
                                        </a>
                                    <?php elseif ($ped['estado'] === 'enviado'): ?>
                                        <a href="pedidos.php?action=change_status&id=<?php echo $ped['id']; ?>&status=entregado" 
                                           class="btn-action btn-deliver" title="Marcar como Entregado">
                                            <i class="fas fa-check"></i>
                                        </a>
                                    <?php endif; ?>
                                    <a href="pedidos.php?action=change_status&id=<?php echo $ped['id']; ?>&status=cancelado" 
                                       class="btn-action btn-cancel" title="Cancelar pedido"
                                       onclick="return confirm('¿Estás seguro de cancelar este pedido?')">
                                        <i class="fas fa-times"></i>
                                    </a>
                                <?php endif; ?>
                                <a href="pedidos.php?action=delete&id=<?php echo $ped['id']; ?>" 
                                   class="btn-action btn-delete" title="Eliminar pedido"
                                   onclick="return confirm('¿Estás seguro de eliminar este pedido?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?php include '../views/admin/layout_end.php'; ?>
