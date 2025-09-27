<?php
require_once '../includes/auth.php';
require_once '../config/database.php';
require_once '../models/Producto.php';
require_once '../models/Categoria.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$database = new Database();
$pdo = $database->getConnection();

$producto = new Producto($pdo);
$categoria = new Categoria($pdo);

$action = $_GET['action'] ?? 'list';
$id = $_GET['id'] ?? null;

switch ($action) {
    case 'create':
        if ($_POST) {
            $data = [
                'nombre' => $_POST['nombre'],
                'descripcion' => $_POST['descripcion'],
                'precio' => $_POST['precio'],
                'stock' => $_POST['stock'],
                'categoria_id' => $_POST['categoria_id'],
                'imagen' => $_POST['imagen'],
                'destacado' => isset($_POST['destacado']) ? 1 : 0,
                'activo' => isset($_POST['activo']) ? 1 : 0
            ];
            
            if ($producto->create($data)) {
                $_SESSION['success'] = 'Producto creado exitosamente';
                header('Location: productos.php');
                exit;
            } else {
                $_SESSION['error'] = 'Error al crear el producto';
            }
        }
        $categorias = $categoria->getAll();
        break;
        
    case 'edit':
        if ($_POST) {
            $data = [
                'nombre' => $_POST['nombre'],
                'descripcion' => $_POST['descripcion'],
                'precio' => $_POST['precio'],
                'stock' => $_POST['stock'],
                'categoria_id' => $_POST['categoria_id'],
                'imagen' => $_POST['imagen'],
                'destacado' => isset($_POST['destacado']) ? 1 : 0,
                'activo' => isset($_POST['activo']) ? 1 : 0
            ];
            
            if ($producto->update($id, $data)) {
                $_SESSION['success'] = 'Producto actualizado exitosamente';
                header('Location: productos.php');
                exit;
            } else {
                $_SESSION['error'] = 'Error al actualizar el producto';
            }
        }
        $producto_data = $producto->getByIdForAdmin($id);
        $categorias = $categoria->getAll();
        break;
        
    case 'delete':
        if ($producto->delete($id)) {
            $_SESSION['success'] = 'Producto eliminado exitosamente';
        } else {
            $_SESSION['error'] = 'Error al eliminar el producto';
        }
        header('Location: productos.php');
        exit;
        
    default:
        $filter = $_GET['filter'] ?? '';
        
        switch ($filter) {
            case 'activos':
                $productos = $producto->getByActivo(1);
                break;
            case 'inactivos':
                $productos = $producto->getByActivo(0);
                break;
            case 'sin_stock':
                $productos = $producto->getByStock(0);
                break;
            case 'stock_bajo':
                $productos = $producto->getByStockRange(1, 5);
                break;
            case 'destacados':
                $productos = $producto->getDestacadosForAdmin();
                break;
            default:
                $productos = $producto->getAllForAdmin();
                break;
        }
        
        $productos_stats = $producto->getStats();
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

    .products-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .product-card {
        background: #ffffff;
        border-radius: 8px;
        border: 1px solid #e5e5e7;
        padding: 1rem;
        transition: all 0.2s ease;
        position: relative;
    }

    .product-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .product-image {
        width: 60px;
        height: 60px;
        border-radius: 8px;
        object-fit: cover;
        margin-bottom: 0.75rem;
    }

    .product-name {
        font-size: 1rem;
        font-weight: 600;
        color: #1d1d1f;
        margin-bottom: 0.5rem;
    }

    .product-price {
        font-size: 1.125rem;
        font-weight: 700;
        color: #34C759;
        margin-bottom: 0.5rem;
    }

    .product-stock {
        font-size: 0.875rem;
        color: #6b7280;
        margin-bottom: 0.75rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .stock-number {
        font-weight: 700;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.75rem;
    }

    .stock-good {
        background: #dcfce7;
        color: #166534;
        border: 1px solid #bbf7d0;
    }

    .stock-low {
        background: #fef3c7;
        color: #92400e;
        border: 1px solid #fde68a;
    }

    .stock-zero {
        background: #fee2e2;
        color: #991b1b;
        border: 1px solid #fecaca;
    }

    .product-badges {
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

    .product-actions {
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

    .filters-row {
        margin-bottom: 1rem;
    }

    .filter-buttons {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .filter-btn {
        padding: 0.5rem 1rem;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.875rem;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        background: #f3f4f6;
        color: #6b7280;
        border: 1px solid #e5e7eb;
    }

    .filter-btn:hover {
        background: #e5e7eb;
        color: #374151;
        transform: translateY(-1px);
    }

    .filter-btn.active {
        background: #007AFF;
        color: #ffffff;
        border-color: #007AFF;
    }

    .filter-btn.active:hover {
        background: #0056CC;
        color: #ffffff;
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
        <h2><?php echo $action === 'create' ? 'Crear Producto' : 'Editar Producto'; ?></h2>
        
        <form method="POST">
            <div class="form-group">
                <label class="form-label" for="nombre">Nombre del Producto</label>
                <input type="text" class="form-input" id="nombre" name="nombre" 
                       value="<?php echo $producto_data['nombre'] ?? ''; ?>" required>
            </div>
            
            <div class="form-group">
                <label class="form-label" for="descripcion">Descripción</label>
                <textarea class="form-input form-textarea" id="descripcion" name="descripcion" required><?php echo $producto_data['descripcion'] ?? ''; ?></textarea>
            </div>
            
            <div class="form-group">
                <label class="form-label" for="precio">Precio (COP)</label>
                <input type="number" class="form-input" id="precio" name="precio" 
                       value="<?php echo $producto_data['precio'] ?? ''; ?>" required>
            </div>
            
            <div class="form-group">
                <label class="form-label" for="stock">Stock</label>
                <input type="number" class="form-input" id="stock" name="stock" 
                       value="<?php echo $producto_data['stock'] ?? ''; ?>" required>
            </div>
            
            <div class="form-group">
                <label class="form-label" for="categoria_id">Categoría</label>
                <select class="form-input form-select" id="categoria_id" name="categoria_id" required>
                    <option value="">Seleccionar categoría</option>
                    <?php foreach ($categorias as $cat): ?>
                        <option value="<?php echo $cat['id']; ?>" 
                                <?php echo (isset($producto_data['categoria_id']) && $producto_data['categoria_id'] == $cat['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($cat['nombre']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label class="form-label" for="imagen">URL de Imagen</label>
                <input type="url" class="form-input" id="imagen" name="imagen" 
                       value="<?php echo $producto_data['imagen_principal'] ?? ''; ?>">
            </div>
            
            <div class="form-group">
                <div class="form-checkbox">
                    <input type="checkbox" id="destacado" name="destacado" 
                           <?php echo (isset($producto_data['destacado']) && $producto_data['destacado']) ? 'checked' : ''; ?>>
                    <label class="form-label" for="destacado">Producto Destacado</label>
                </div>
            </div>
            
            <div class="form-group">
                <div class="form-checkbox">
                    <input type="checkbox" id="activo" name="activo" 
                           <?php echo (isset($producto_data['activo']) && $producto_data['activo']) ? 'checked' : ''; ?>>
                    <label class="form-label" for="activo">Producto Activo</label>
                </div>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn-save">
                    <i class="fas fa-save"></i> Guardar
                </button>
                <a href="productos.php" class="btn-cancel">
                    <i class="fas fa-times"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
<?php else: ?>
    <div class="page-header">
        <h2>Gestión de Productos</h2>
        <a href="productos.php?action=create" class="btn-create">
            <i class="fas fa-plus"></i> Nuevo Producto
        </a>
    </div>

    <div class="filters-row">
        <div class="filter-buttons">
            <a href="productos.php" class="filter-btn <?php echo !isset($_GET['filter']) ? 'active' : ''; ?>">
                <i class="fas fa-th"></i> Todos
            </a>
            <a href="productos.php?filter=activos" class="filter-btn <?php echo ($_GET['filter'] ?? '') === 'activos' ? 'active' : ''; ?>">
                <i class="fas fa-check-circle"></i> Activos
            </a>
            <a href="productos.php?filter=inactivos" class="filter-btn <?php echo ($_GET['filter'] ?? '') === 'inactivos' ? 'active' : ''; ?>">
                <i class="fas fa-times-circle"></i> Inactivos
            </a>
            <a href="productos.php?filter=sin_stock" class="filter-btn <?php echo ($_GET['filter'] ?? '') === 'sin_stock' ? 'active' : ''; ?>">
                <i class="fas fa-exclamation-triangle"></i> Sin Stock
            </a>
            <a href="productos.php?filter=stock_bajo" class="filter-btn <?php echo ($_GET['filter'] ?? '') === 'stock_bajo' ? 'active' : ''; ?>">
                <i class="fas fa-exclamation-circle"></i> Stock Bajo
            </a>
            <a href="productos.php?filter=destacados" class="filter-btn <?php echo ($_GET['filter'] ?? '') === 'destacados' ? 'active' : ''; ?>">
                <i class="fas fa-star"></i> Destacados
            </a>
        </div>
    </div>

    <div class="stats-row">
        <div class="stat-item">
            <div class="stat-number"><?php echo $productos_stats['total_productos']; ?></div>
            <div class="stat-label">Total Productos</div>
        </div>
        <div class="stat-item">
            <div class="stat-number"><?php echo $productos_stats['productos_activos']; ?></div>
            <div class="stat-label">Productos Activos</div>
        </div>
        <div class="stat-item">
            <div class="stat-number"><?php echo $productos_stats['productos_inactivos']; ?></div>
            <div class="stat-label">Productos Inactivos</div>
        </div>
        <div class="stat-item">
            <div class="stat-number"><?php echo $productos_stats['productos_destacados']; ?></div>
            <div class="stat-label">Productos Destacados</div>
        </div>
        <div class="stat-item">
            <div class="stat-number"><?php echo $productos_stats['stock_total']; ?></div>
            <div class="stat-label">Stock Total</div>
        </div>
        <div class="stat-item">
            <div class="stat-number"><?php echo $productos_stats['productos_sin_stock']; ?></div>
            <div class="stat-label">Sin Stock</div>
        </div>
        <div class="stat-item">
            <div class="stat-number"><?php echo $productos_stats['stock_bajo']; ?></div>
            <div class="stat-label">Stock Bajo (≤5)</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">$<?php echo number_format($productos_stats['precio_promedio'], 0, ',', '.'); ?></div>
            <div class="stat-label">Precio Promedio</div>
        </div>
    </div>

    <div class="products-grid">
        <?php foreach ($productos as $prod): ?>
            <div class="product-card">
                <?php if ($prod['imagen_principal']): ?>
                    <img src="<?php echo htmlspecialchars($prod['imagen_principal']); ?>" 
                         alt="<?php echo htmlspecialchars($prod['nombre']); ?>" 
                         class="product-image">
                <?php else: ?>
                    <div class="product-image" style="background: #f3f4f6; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-image" style="color: #9ca3af;"></i>
                    </div>
                <?php endif; ?>
                
                <div class="product-name"><?php echo htmlspecialchars($prod['nombre']); ?></div>
                <div class="product-price">$<?php echo number_format($prod['precio'], 0, ',', '.'); ?></div>
                
                <div class="product-stock">
                    <i class="fas fa-boxes"></i> Stock: 
                    <span class="stock-number <?php 
                        if ($prod['stock'] == 0) echo 'stock-zero';
                        elseif ($prod['stock'] <= 5) echo 'stock-low';
                        else echo 'stock-good';
                    ?>"><?php echo $prod['stock']; ?></span>
                </div>
                
                <div class="product-badges">
                    <?php if ($prod['destacado']): ?>
                        <span class="badge badge-warning">
                            <i class="fas fa-star"></i> Destacado
                        </span>
                    <?php endif; ?>
                    <?php if ($prod['activo']): ?>
                        <span class="badge badge-success">
                            <i class="fas fa-check-circle"></i> Activo
                        </span>
                    <?php else: ?>
                        <span class="badge badge-error">
                            <i class="fas fa-times-circle"></i> Inactivo
                        </span>
                    <?php endif; ?>
                    <?php if ($prod['stock'] == 0): ?>
                        <span class="badge badge-error">
                            <i class="fas fa-exclamation-triangle"></i> Sin Stock
                        </span>
                    <?php elseif ($prod['stock'] <= 5): ?>
                        <span class="badge badge-warning">
                            <i class="fas fa-exclamation-circle"></i> Stock Bajo
                        </span>
                    <?php endif; ?>
                </div>
                
                <div class="product-actions">
                    <a href="productos.php?action=edit&id=<?php echo $prod['id']; ?>" 
                       class="btn-action btn-edit">
                        <i class="fas fa-edit"></i> Editar
                    </a>
                    <a href="productos.php?action=delete&id=<?php echo $prod['id']; ?>" 
                       class="btn-action btn-delete"
                       onclick="return confirm('¿Estás seguro de eliminar este producto?')">
                        <i class="fas fa-trash"></i> Eliminar
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php include '../views/admin/layout_end.php'; ?>
