<?php
require_once '../includes/auth.php';
require_once '../config/database.php';
require_once '../models/Usuario.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$database = new Database();
$pdo = $database->getConnection();

$usuario = new Usuario($pdo);

$action = $_GET['action'] ?? 'list';
$id = $_GET['id'] ?? null;

switch ($action) {
    case 'create':
        if ($_POST) {
            $data = [
                'nombre' => $_POST['nombre'],
                'apellido' => $_POST['apellido'],
                'email' => $_POST['email'],
                'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
                'telefono' => $_POST['telefono'],
                'direccion' => $_POST['direccion'],
                'rol' => $_POST['rol'],
                'activo' => isset($_POST['activo']) ? 1 : 0
            ];
            
            if ($usuario->create($data)) {
                $_SESSION['success'] = 'Usuario creado exitosamente';
                header('Location: usuarios.php');
                exit;
            } else {
                $_SESSION['error'] = 'Error al crear el usuario';
            }
        }
        break;
        
    case 'edit':
        if ($_POST) {
            $data = [
                'nombre' => $_POST['nombre'],
                'apellido' => $_POST['apellido'],
                'email' => $_POST['email'],
                'telefono' => $_POST['telefono'],
                'direccion' => $_POST['direccion'],
                'rol' => $_POST['rol'],
                'activo' => isset($_POST['activo']) ? 1 : 0
            ];
            
            if (!empty($_POST['password'])) {
                $data['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
            }
            
            if ($usuario->update($id, $data)) {
                $_SESSION['success'] = 'Usuario actualizado exitosamente';
                header('Location: usuarios.php');
                exit;
            } else {
                $_SESSION['error'] = 'Error al actualizar el usuario';
            }
        }
        $usuario_data = $usuario->getById($id);
        break;
        
    case 'delete':
        if ($usuario->delete($id)) {
            $_SESSION['success'] = 'Usuario eliminado exitosamente';
        } else {
            $_SESSION['error'] = 'Error al eliminar el usuario';
        }
        header('Location: usuarios.php');
        exit;
        
    default:
        $usuarios = $usuario->getAll();
        $usuarios_stats = $usuario->getStats();
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

    .users-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .user-card {
        background: #ffffff;
        border-radius: 8px;
        border: 1px solid #e5e5e7;
        padding: 1rem;
        transition: all 0.2s ease;
        position: relative;
    }

    .user-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .user-header {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 0.75rem;
    }

    .user-avatar {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        background: linear-gradient(135deg, #007AFF, #00D4FF);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        font-size: 1.125rem;
    }

    .user-info h3 {
        font-size: 1rem;
        font-weight: 600;
        color: #1d1d1f;
        margin: 0 0 0.125rem 0;
    }

    .user-email {
        font-size: 0.75rem;
        color: #6b7280;
    }

    .user-details {
        margin-bottom: 0.75rem;
    }

    .detail-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.25rem;
        font-size: 0.75rem;
    }

    .detail-label {
        color: #6b7280;
        font-weight: 500;
    }

    .detail-value {
        color: #1d1d1f;
        font-weight: 600;
    }

    .user-badges {
        display: flex;
        gap: 0.25rem;
        margin-bottom: 0.75rem;
    }

    .badge {
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.625rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .badge-admin {
        background: #fef3c7;
        color: #92400e;
        border: 1px solid #fde68a;
    }

    .badge-user {
        background: #dbeafe;
        color: #1e40af;
        border: 1px solid #bfdbfe;
    }

    .badge-success {
        background: #dcfce7;
        color: #166534;
        border: 1px solid #bbf7d0;
    }

    .badge-error {
        background: #fee2e2;
        color: #991b1b;
        border: 1px solid #fecaca;
    }

    .user-actions {
        display: flex;
        gap: 0.25rem;
    }

    .btn-action {
        flex: 1;
        padding: 0.375rem;
        border-radius: 6px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.2s ease;
        font-size: 0.75rem;
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

    .form-select {
        appearance: none;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
        background-position: right 0.5rem center;
        background-repeat: no-repeat;
        background-size: 1.5em 1.5em;
        padding-right: 2.5rem;
    }

    .form-checkbox {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .form-checkbox input {
        width: 16px;
        height: 16px;
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
        <h2><?php echo $action === 'create' ? 'Crear Usuario' : 'Editar Usuario'; ?></h2>
        
        <form method="POST">
            <div class="form-group">
                <label class="form-label" for="nombre">Nombre</label>
                <input type="text" class="form-input" id="nombre" name="nombre" 
                       value="<?php echo $usuario_data['nombre'] ?? ''; ?>" required>
            </div>
            
            <div class="form-group">
                <label class="form-label" for="apellido">Apellido</label>
                <input type="text" class="form-input" id="apellido" name="apellido" 
                       value="<?php echo $usuario_data['apellido'] ?? ''; ?>" required>
            </div>
            
            <div class="form-group">
                <label class="form-label" for="email">Email</label>
                <input type="email" class="form-input" id="email" name="email" 
                       value="<?php echo $usuario_data['email'] ?? ''; ?>" required>
            </div>
            
            <div class="form-group">
                <label class="form-label" for="password">Contraseña <?php echo $action === 'edit' ? '(dejar vacío para mantener la actual)' : ''; ?></label>
                <input type="password" class="form-input" id="password" name="password" 
                       <?php echo $action === 'create' ? 'required' : ''; ?>>
            </div>
            
            <div class="form-group">
                <label class="form-label" for="telefono">Teléfono</label>
                <input type="tel" class="form-input" id="telefono" name="telefono" 
                       value="<?php echo $usuario_data['telefono'] ?? ''; ?>">
            </div>
            
            <div class="form-group">
                <label class="form-label" for="direccion">Dirección</label>
                <input type="text" class="form-input" id="direccion" name="direccion" 
                       value="<?php echo $usuario_data['direccion'] ?? ''; ?>">
            </div>
            
            <div class="form-group">
                <label class="form-label" for="rol">Rol</label>
                <select class="form-input form-select" id="rol" name="rol" required>
                    <option value="">Seleccionar rol</option>
                    <option value="admin" <?php echo (isset($usuario_data['rol']) && $usuario_data['rol'] === 'admin') ? 'selected' : ''; ?>>Administrador</option>
                    <option value="usuario" <?php echo (isset($usuario_data['rol']) && $usuario_data['rol'] === 'usuario') ? 'selected' : ''; ?>>Usuario</option>
                </select>
            </div>
            
            <div class="form-group">
                <div class="form-checkbox">
                    <input type="checkbox" id="activo" name="activo" 
                           <?php echo (isset($usuario_data['activo']) && $usuario_data['activo']) ? 'checked' : ''; ?>>
                    <label class="form-label" for="activo">Usuario Activo</label>
                </div>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn-save">
                    <i class="fas fa-save"></i> Guardar
                </button>
                <a href="usuarios.php" class="btn-cancel">
                    <i class="fas fa-times"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
<?php else: ?>
    <div class="page-header">
        <h2>Gestión de Usuarios</h2>
        <a href="usuarios.php?action=create" class="btn-create">
            <i class="fas fa-plus"></i> Nuevo Usuario
        </a>
    </div>

    <div class="stats-row">
        <div class="stat-item">
            <div class="stat-number"><?php echo $usuarios_stats['total_usuarios']; ?></div>
            <div class="stat-label">Total Usuarios</div>
        </div>
        <div class="stat-item">
            <div class="stat-number"><?php echo $usuarios_stats['usuarios_activos']; ?></div>
            <div class="stat-label">Usuarios Activos</div>
        </div>
        <div class="stat-item">
            <div class="stat-number"><?php echo $usuarios_stats['usuarios_admin']; ?></div>
            <div class="stat-label">Administradores</div>
        </div>
    </div>

    <div class="users-grid">
        <?php foreach ($usuarios as $user): ?>
            <div class="user-card">
                <div class="user-header">
                    <div class="user-avatar">
                        <?php echo strtoupper(substr($user['nombre'], 0, 1) . substr($user['apellido'], 0, 1)); ?>
                    </div>
                    <div class="user-info">
                        <h3><?php echo htmlspecialchars($user['nombre'] . ' ' . $user['apellido']); ?></h3>
                        <div class="user-email"><?php echo htmlspecialchars($user['email']); ?></div>
                    </div>
                </div>
                
                <div class="user-details">
                    <div class="detail-row">
                        <span class="detail-label">Teléfono:</span>
                        <span class="detail-value"><?php echo htmlspecialchars($user['telefono'] ?? 'No especificado'); ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Dirección:</span>
                        <span class="detail-value"><?php echo htmlspecialchars($user['direccion'] ?? 'No especificada'); ?></span>
                    </div>
                </div>
                
                <div class="user-badges">
                    <span class="badge <?php echo $user['rol'] === 'admin' ? 'badge-admin' : 'badge-user'; ?>">
                        <?php echo $user['rol'] === 'admin' ? 'Admin' : 'Usuario'; ?>
                    </span>
                    <span class="badge <?php echo $user['activo'] ? 'badge-success' : 'badge-error'; ?>">
                        <?php echo $user['activo'] ? 'Activo' : 'Inactivo'; ?>
                    </span>
                </div>
                
                <div class="user-actions">
                    <a href="usuarios.php?action=edit&id=<?php echo $user['id']; ?>" 
                       class="btn-action btn-edit">
                        <i class="fas fa-edit"></i> Editar
                    </a>
                    <a href="usuarios.php?action=delete&id=<?php echo $user['id']; ?>" 
                       class="btn-action btn-delete"
                       onclick="return confirm('¿Estás seguro de eliminar este usuario?')">
                        <i class="fas fa-trash"></i> Eliminar
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php include '../views/admin/layout_end.php'; ?>
