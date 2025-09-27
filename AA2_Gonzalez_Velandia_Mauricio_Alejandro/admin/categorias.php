<?php
require_once '../includes/auth.php';
require_once '../config/database.php';
require_once '../models/Categoria.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$database = new Database();
$pdo = $database->getConnection();

$categoria = new Categoria($pdo);

$action = $_GET['action'] ?? 'list';
$id = $_GET['id'] ?? null;

switch ($action) {
    case 'create':
        if ($_POST) {
            $data = [
                'nombre' => $_POST['nombre'],
                'descripcion' => $_POST['descripcion'],
                'icono' => $_POST['icono'],
                'color' => $_POST['color'],
                'activo' => isset($_POST['activo']) ? 1 : 0
            ];
            
            if ($categoria->create($data)) {
                $_SESSION['success'] = 'Categoría creada exitosamente';
                header('Location: categorias.php');
                exit;
            } else {
                $_SESSION['error'] = 'Error al crear la categoría';
            }
        }
        break;
        
    case 'edit':
        if ($_POST) {
            $data = [
                'nombre' => $_POST['nombre'],
                'descripcion' => $_POST['descripcion'],
                'icono' => $_POST['icono'],
                'color' => $_POST['color'],
                'activo' => isset($_POST['activo']) ? 1 : 0
            ];
            
            if ($categoria->update($id, $data)) {
                $_SESSION['success'] = 'Categoría actualizada exitosamente';
                header('Location: categorias.php');
                exit;
            } else {
                $_SESSION['error'] = 'Error al actualizar la categoría';
            }
        }
        $categoria_data = $categoria->getById($id);
        break;
        
    case 'delete':
        if ($categoria->delete($id)) {
            $_SESSION['success'] = 'Categoría eliminada exitosamente';
        } else {
            $_SESSION['error'] = 'Error al eliminar la categoría';
        }
        header('Location: categorias.php');
        exit;
        
    default:
        $categorias = $categoria->getAll();
        $categorias_stats = $categoria->getStats();
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

    .categories-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .category-card {
        background: #ffffff;
        border-radius: 8px;
        border: 1px solid #e5e5e7;
        padding: 1rem;
        transition: all 0.2s ease;
        position: relative;
    }

    .category-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .category-header {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 0.75rem;
    }

    .category-icon {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        color: white;
    }

    .category-info h3 {
        font-size: 1rem;
        font-weight: 600;
        color: #1d1d1f;
        margin: 0;
    }

    .category-description {
        color: #6b7280;
        font-size: 0.75rem;
        margin-bottom: 0.75rem;
    }

    .category-stats {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.75rem;
    }

    .product-count {
        font-size: 0.875rem;
        font-weight: 600;
        color: #007AFF;
    }

    .category-status {
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.625rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-active {
        background: #dcfce7;
        color: #166534;
        border: 1px solid #bbf7d0;
    }

    .status-inactive {
        background: #fee2e2;
        color: #991b1b;
        border: 1px solid #fecaca;
    }

    .category-actions {
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

    .form-textarea {
        min-height: 80px;
        resize: vertical;
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

    .icon-preview {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        color: white;
        margin-top: 0.5rem;
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
        <h2><?php echo $action === 'create' ? 'Crear Categoría' : 'Editar Categoría'; ?></h2>
        
        <form method="POST">
            <div class="form-group">
                <label class="form-label" for="nombre">Nombre de la Categoría</label>
                <input type="text" class="form-input" id="nombre" name="nombre" 
                       value="<?php echo $categoria_data['nombre'] ?? ''; ?>" required>
            </div>
            
            <div class="form-group">
                <label class="form-label" for="descripcion">Descripción</label>
                <textarea class="form-input form-textarea" id="descripcion" name="descripcion" required><?php echo $categoria_data['descripcion'] ?? ''; ?></textarea>
            </div>
            
            <div class="form-group">
                <label class="form-label" for="icono">Icono (Font Awesome)</label>
                <input type="text" class="form-input" id="icono" name="icono" 
                       value="<?php echo $categoria_data['icono'] ?? 'fas fa-tag'; ?>" 
                       placeholder="fas fa-tag">
                <div class="icon-preview" id="icon-preview" 
                     style="background: <?php echo $categoria_data['color'] ?? '#007AFF'; ?>;">
                    <i class="<?php echo $categoria_data['icono'] ?? 'fas fa-tag'; ?>"></i>
                </div>
            </div>
            
            <div class="form-group">
                <label class="form-label" for="color">Color</label>
                <input type="color" class="form-input" id="color" name="color" 
                       value="<?php echo $categoria_data['color'] ?? '#007AFF'; ?>">
            </div>
            
            <div class="form-group">
                <div class="form-checkbox">
                    <input type="checkbox" id="activo" name="activo" 
                           <?php echo (isset($categoria_data['activo']) && $categoria_data['activo']) ? 'checked' : ''; ?>>
                    <label class="form-label" for="activo">Categoría Activa</label>
                </div>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn-save">
                    <i class="fas fa-save"></i> Guardar
                </button>
                <a href="categorias.php" class="btn-cancel">
                    <i class="fas fa-times"></i> Cancelar
                </a>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('icono').addEventListener('input', function() {
            const icon = this.value;
            const preview = document.getElementById('icon-preview');
            preview.innerHTML = `<i class="${icon}"></i>`;
        });

        document.getElementById('color').addEventListener('input', function() {
            const color = this.value;
            const preview = document.getElementById('icon-preview');
            preview.style.background = color;
        });
    </script>
<?php else: ?>
    <div class="page-header">
        <h2>Gestión de Categorías</h2>
        <a href="categorias.php?action=create" class="btn-create">
            <i class="fas fa-plus"></i> Nueva Categoría
        </a>
    </div>

    <div class="stats-row">
        <div class="stat-item">
            <div class="stat-number"><?php echo $categorias_stats['total_categorias']; ?></div>
            <div class="stat-label">Total Categorías</div>
        </div>
        <div class="stat-item">
            <div class="stat-number"><?php echo $categorias_stats['categorias_activas']; ?></div>
            <div class="stat-label">Categorías Activas</div>
        </div>
    </div>

    <div class="categories-grid">
        <?php foreach ($categorias as $cat): ?>
            <div class="category-card">
                <div class="category-header">
                    <div class="category-icon" style="background: <?php echo $cat['color']; ?>;">
                        <i class="<?php echo $cat['icono']; ?>"></i>
                    </div>
                    <div class="category-info">
                        <h3><?php echo htmlspecialchars($cat['nombre']); ?></h3>
                    </div>
                </div>
                
                <div class="category-description">
                    <?php echo htmlspecialchars($cat['descripcion']); ?>
                </div>
                
                <div class="category-stats">
                    <span class="product-count"><?php echo $cat['productos_count']; ?> productos</span>
                    <span class="category-status <?php echo $cat['activo'] ? 'status-active' : 'status-inactive'; ?>">
                        <?php echo $cat['activo'] ? 'Activa' : 'Inactiva'; ?>
                    </span>
                </div>
                
                <div class="category-actions">
                    <a href="categorias.php?action=edit&id=<?php echo $cat['id']; ?>" 
                       class="btn-action btn-edit">
                        <i class="fas fa-edit"></i> Editar
                    </a>
                    <a href="categorias.php?action=delete&id=<?php echo $cat['id']; ?>" 
                       class="btn-action btn-delete"
                       onclick="return confirm('¿Estás seguro de eliminar esta categoría?')">
                        <i class="fas fa-trash"></i> Eliminar
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php include '../views/admin/layout_end.php'; ?>
