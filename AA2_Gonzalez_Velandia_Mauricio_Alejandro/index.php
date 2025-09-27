<?php
session_start();
require_once 'config/database.php';
require_once 'models/Producto.php';
require_once 'models/Categoria.php';

$database = new Database();
$pdo = $database->getConnection();

$producto = new Producto($pdo);
$categoria = new Categoria($pdo);

if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_to_cart') {
    $producto_id = $_POST['producto_id'] ?? 0;
    $cantidad = $_POST['cantidad'] ?? 1;
    
    if ($producto_id && $cantidad > 0) {
        $producto_data = $producto->getById($producto_id);
        if ($producto_data) {
            $cantidad_actual = $_SESSION['carrito'][$producto_id] ?? 0;
            $cantidad_total = $cantidad_actual + $cantidad;
            
            if ($cantidad_total > $producto_data['stock']) {
                $error_message = "Stock insuficiente. Solo hay {$producto_data['stock']} unidades disponibles de {$producto_data['nombre']}";
            } else {
                $_SESSION['carrito'][$producto_id] = $cantidad_total;
                $success_message = "Producto agregado al carrito exitosamente";
            }
        } else {
            $error_message = "Producto no encontrado";
        }
    }
}

$productos_destacados = $producto->getDestacados(8);
$categorias = $categoria->getAll();
$productos_recientes = $producto->getRecent(6);

$search = $_GET['search'] ?? '';
$categoria_id = $_GET['categoria'] ?? '';

if (!empty($search) || !empty($categoria_id)) {
    $productos_filtrados = $producto->search($search, $categoria_id);
} else {
    $productos_filtrados = [];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda Online - Productos de Calidad</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: #f8f9fa;
            color: #1d1d1f;
            line-height: 1.6;
        }

        .header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            padding: 1.25rem 0;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 1px 20px rgba(0, 0, 0, 0.05);
        }

        .header-content {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 2rem;
        }

        .logo {
            font-size: 1.75rem;
            font-weight: 800;
            background: linear-gradient(135deg, #007AFF, #00D4FF);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transition: all 0.3s ease;
        }

        .logo:hover {
            transform: scale(1.05);
        }

        .logo i {
            font-size: 2rem;
            background: linear-gradient(135deg, #007AFF, #00D4FF);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .nav {
            display: flex;
            gap: 2.5rem;
            align-items: center;
        }

        .nav a {
            color: #1d1d1f;
            text-decoration: none;
            font-weight: 600;
            font-size: 1rem;
            padding: 0.75rem 1.25rem;
            border-radius: 12px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .nav a::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #007AFF, #00D4FF);
            transition: left 0.3s ease;
            z-index: -1;
        }

        .nav a:hover {
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 122, 255, 0.3);
        }

        .nav a:hover::before {
            left: 0;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .welcome-text {
            background: linear-gradient(135deg, #f8f9fa, #ffffff);
            color: #1d1d1f;
            font-size: 0.875rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.875rem 1.25rem;
            border-radius: 16px;
            border: 1px solid rgba(0, 122, 255, 0.1);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .welcome-text::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(0, 122, 255, 0.1), rgba(0, 212, 255, 0.1));
            transition: left 0.3s ease;
            z-index: -1;
        }

        .welcome-text:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(0, 122, 255, 0.15);
            border-color: rgba(0, 122, 255, 0.2);
        }

        .welcome-text:hover::before {
            left: 0;
        }

        .welcome-text i {
            font-size: 1.125rem;
            background: linear-gradient(135deg, #007AFF, #00D4FF);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .search-bar {
            display: flex;
            align-items: center;
            position: relative;
        }

        .search-bar form {
            display: flex;
            align-items: center;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border-radius: 25px;
            border: 1px solid rgba(0, 122, 255, 0.15);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .search-bar form:hover {
            box-shadow: 0 8px 30px rgba(0, 122, 255, 0.15);
            border-color: rgba(0, 122, 255, 0.25);
        }

        .search-input {
            padding: 0.75rem 1.5rem;
            border: none;
            background: transparent;
            font-size: 0.875rem;
            width: 250px;
            transition: all 0.3s ease;
            font-weight: 500;
            color: #1d1d1f;
        }

        .search-input:focus {
            outline: none;
            width: 280px;
        }

        .search-input::placeholder {
            color: #8e8e93;
            font-weight: 400;
        }

        .search-bar .btn {
            padding: 0.75rem 1.25rem;
            border-radius: 0;
            background: linear-gradient(135deg, #007AFF, #00D4FF);
            border: none;
            margin: 0;
            box-shadow: none;
        }

        .search-bar .btn:hover {
            background: linear-gradient(135deg, #0056CC, #0099CC);
            transform: none;
        }

        .btn {
            padding: 0.875rem 1.5rem;
            border-radius: 16px;
            text-decoration: none;
            font-weight: 700;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            font-size: 0.875rem;
            position: relative;
            overflow: hidden;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.2), rgba(255, 255, 255, 0.1));
            transition: left 0.3s ease;
            z-index: 1;
        }

        .btn:hover::before {
            left: 0;
        }

        .btn-primary {
            background: linear-gradient(135deg, #007AFF, #00D4FF);
            color: #ffffff;
            box-shadow: 0 4px 20px rgba(0, 122, 255, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 30px rgba(0, 122, 255, 0.4);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.8);
            color: #1d1d1f;
            border: 2px solid rgba(0, 122, 255, 0.1);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.95);
            border-color: rgba(0, 122, 255, 0.2);
            transform: translateY(-2px);
            box-shadow: 0 6px 25px rgba(0, 0, 0, 0.1);
        }

        .user-actions {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .user-actions a {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.25rem;
            padding: 0.75rem;
            border-radius: 12px;
            text-decoration: none;
            color: #1d1d1f;
            font-weight: 600;
            font-size: 0.75rem;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.6);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(0, 122, 255, 0.1);
            min-width: 60px;
            position: relative;
            overflow: hidden;
        }

        .user-actions a::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(0, 122, 255, 0.1), rgba(0, 212, 255, 0.1));
            transition: left 0.3s ease;
            z-index: -1;
        }

        .user-actions a:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 122, 255, 0.2);
            border-color: rgba(0, 122, 255, 0.2);
            background: rgba(255, 255, 255, 0.9);
        }

        .user-actions a:hover::before {
            left: 0;
        }

        .user-actions a i {
            font-size: 1.25rem;
            background: linear-gradient(135deg, #007AFF, #00D4FF);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            transition: all 0.3s ease;
        }

        .user-actions a:hover i {
            transform: scale(1.1);
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        .hero {
            background: linear-gradient(135deg, #007AFF, #00D4FF);
            color: #ffffff;
            padding: 4rem 0;
            text-align: center;
        }

        .hero h1 {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .hero p {
            font-size: 1.25rem;
            opacity: 0.9;
            margin-bottom: 2rem;
        }

        .section {
            padding: 3rem 0;
        }

        .section-title {
            font-size: 2rem;
            font-weight: 700;
            color: #1d1d1f;
            margin-bottom: 2rem;
            text-align: center;
        }

        .categories {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .category-card {
            background: #ffffff;
            border-radius: 12px;
            padding: 1.5rem;
            text-align: center;
            transition: all 0.3s ease;
            border: 1px solid #e5e5e7;
            text-decoration: none;
            color: inherit;
        }

        .category-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .category-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
            margin: 0 auto 1rem auto;
        }

        .category-name {
            font-size: 1.125rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .category-count {
            font-size: 0.875rem;
            color: #6b7280;
        }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
        }

        .product-card {
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            transition: all 0.3s ease;
            border: 1px solid #e5e5e7;
        }

        .product-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .product-image {
            width: 100%;
            height: 200px;
            background: #f3f4f6;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            color: #d1d5db;
        }

        .product-info {
            padding: 1.5rem;
        }

        .product-name {
            font-size: 1.125rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #1d1d1f;
        }

        .product-description {
            font-size: 0.875rem;
            color: #6b7280;
            margin-bottom: 1rem;
            line-height: 1.5;
        }

        .product-price {
            font-size: 1.25rem;
            font-weight: 700;
            color: #34C759;
            margin-bottom: 1rem;
        }

        .product-stock {
            font-size: 0.875rem;
            color: #6b7280;
            margin-bottom: 1rem;
        }

        .product-badges {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 1rem;
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
        }

        .badge-warning {
            background: #fef3c7;
            color: #92400e;
        }

        .product-actions {
            display: flex;
            gap: 0.5rem;
        }

        .btn-secondary {
            background: #f3f4f6;
            color: #374151;
            border: 1px solid #d1d5db;
        }

        .btn-secondary:hover {
            background: #e5e7eb;
        }

        .filters {
            background: #ffffff;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            border: 1px solid #e5e5e7;
        }

        .filters-content {
            display: flex;
            gap: 1rem;
            align-items: center;
            flex-wrap: wrap;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .filter-label {
            font-size: 0.875rem;
            font-weight: 600;
            color: #374151;
        }

        .filter-select {
            padding: 0.5rem;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 0.875rem;
        }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: #6b7280;
        }

        .empty-state i {
            font-size: 4rem;
            margin-bottom: 1rem;
            color: #d1d5db;
        }

        .footer {
            background: #1d1d1f;
            color: #ffffff;
            padding: 3rem 0;
            text-align: center;
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        .footer h3 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .footer p {
            opacity: 0.8;
            margin-bottom: 2rem;
        }

        .footer-links {
            display: flex;
            justify-content: center;
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .footer-links a {
            color: #ffffff;
            text-decoration: none;
            opacity: 0.8;
            transition: opacity 0.2s ease;
        }

        .footer-links a:hover {
            opacity: 1;
        }

        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                gap: 1rem;
            }

            .nav {
                gap: 1rem;
            }

            .search-input {
                width: 200px;
            }

            .hero h1 {
                font-size: 2rem;
            }

            .hero p {
                font-size: 1rem;
            }

            .products-grid {
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            }

            .filters-content {
                flex-direction: column;
                align-items: stretch;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="header-content">
            <a href="index.php" class="logo">
                <i class="fas fa-store"></i> Tienda Online
            </a>
            <nav class="nav">
                <a href="index.php">Inicio</a>
                <a href="#productos">Productos</a>
                <a href="#categorias">Categorías</a>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <div class="user-info">
                        <span class="welcome-text">
                            <i class="fas fa-user-circle"></i> 
                            ¡Hola, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!
                        </span>
                    </div>
                    <div class="user-actions">
                        <a href="public/mi_cuenta.php">
                            <i class="fas fa-user"></i> Mi Cuenta
                        </a>
                        <a href="public/carrito.php">
                            <i class="fas fa-shopping-cart"></i> Carrito
                        </a>
                        <a href="public/logout.php">
                            <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                        </a>
                        <a href="admin/">
                            <i class="fas fa-cog"></i> Admin
                        </a>
                    </div>
                <?php else: ?>
                    <div class="user-actions">
                        <a href="public/login.php">
                            <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
                        </a>
                        <a href="public/register.php">
                            <i class="fas fa-user-plus"></i> Registrarse
                        </a>
                        <a href="admin/">
                            <i class="fas fa-cog"></i> Admin
                        </a>
                    </div>
                <?php endif; ?>
            </nav>
            <div class="search-bar">
                <form method="GET">
                    <input type="text" name="search" class="search-input" placeholder="Buscar productos..." value="<?php echo htmlspecialchars($search); ?>">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>
        </div>
    </header>

    <?php if (!empty($error_message)): ?>
        <div style="background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; padding: 1rem; margin: 1rem auto; max-width: 1200px; border-radius: 8px; text-align: center;">
            <i class="fas fa-exclamation-triangle"></i> <?php echo htmlspecialchars($error_message); ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($success_message)): ?>
        <div style="background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; padding: 1rem; margin: 1rem auto; max-width: 1200px; border-radius: 8px; text-align: center;">
            <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($success_message); ?>
        </div>
    <?php endif; ?>

    <section class="hero">
        <div class="container">
            <h1>Bienvenido a Nuestra Tienda</h1>
            <p>Descubre productos de calidad con los mejores precios</p>
            <a href="#productos" class="btn btn-primary">Ver Productos</a>
        </div>
    </section>

    <section class="section" id="categorias">
        <div class="container">
            <h2 class="section-title">Nuestras Categorías</h2>
            <div class="categories">
                <?php foreach ($categorias as $cat): ?>
                    <a href="?categoria=<?php echo $cat['id']; ?>" class="category-card">
                        <div class="category-icon" style="background: <?php echo $cat['color']; ?>;">
                            <i class="<?php echo $cat['icono']; ?>"></i>
                        </div>
                        <div class="category-name"><?php echo htmlspecialchars($cat['nombre']); ?></div>
                        <div class="category-count"><?php echo $cat['productos_count']; ?> productos</div>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="section" id="productos">
        <div class="container">
            <?php if (!empty($search) || !empty($categoria_id)): ?>
                <div class="filters">
                    <div class="filters-content">
                        <div class="filter-group">
                            <label class="filter-label">Categoría:</label>
                            <select name="categoria" class="filter-select" onchange="this.form.submit()">
                                <option value="">Todas las categorías</option>
                                <?php foreach ($categorias as $cat): ?>
                                    <option value="<?php echo $cat['id']; ?>" <?php echo $categoria_id == $cat['id'] ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($cat['nombre']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label class="filter-label">Resultados:</label>
                            <span><?php echo count($productos_filtrados); ?> productos encontrados</span>
                        </div>
                    </div>
                </div>

                <?php if (!empty($productos_filtrados)): ?>
                    <div class="products-grid">
                        <?php foreach ($productos_filtrados as $prod): ?>
                            <div class="product-card">
                                <div class="product-image">
                                    <i class="fas fa-image"></i>
                                </div>
                                <div class="product-info">
                                    <h3 class="product-name"><?php echo htmlspecialchars($prod['nombre']); ?></h3>
                                    <p class="product-description"><?php echo htmlspecialchars($prod['descripcion']); ?></p>
                                    <div class="product-price">$<?php echo number_format($prod['precio'], 0, ',', '.'); ?> COP</div>
                                    <div class="product-stock">Stock: <?php echo $prod['stock']; ?> unidades</div>
                                    <div class="product-badges">
                                        <?php if ($prod['destacado']): ?>
                                            <span class="badge badge-warning">Destacado</span>
                                        <?php endif; ?>
                                        <?php if ($prod['activo']): ?>
                                            <span class="badge badge-success">Disponible</span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="product-actions">
                                        <?php if (isset($_SESSION['user_id'])): ?>
                                            <form method="POST" action="public/carrito.php" style="display: inline;">
                                                <input type="hidden" name="action" value="add">
                                                <input type="hidden" name="producto_id" value="<?php echo $prod['id']; ?>">
                                                <input type="hidden" name="cantidad" value="1">
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fas fa-shopping-cart"></i> Agregar al Carrito
                                                </button>
                                            </form>
                                        <?php else: ?>
                                            <a href="public/login.php" class="btn btn-primary">
                                                <i class="fas fa-sign-in-alt"></i> Iniciar Sesión para Comprar
                                            </a>
                                        <?php endif; ?>
                                        <button class="btn btn-secondary">
                                            <i class="fas fa-heart"></i> Favorito
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <i class="fas fa-search"></i>
                        <h3>No se encontraron productos</h3>
                        <p>Intenta con otros términos de búsqueda o explora nuestras categorías</p>
                        <a href="index.php" class="btn btn-primary">Ver Todos los Productos</a>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <h2 class="section-title">Productos Destacados</h2>
                <?php if (!empty($productos_destacados)): ?>
                    <div class="products-grid">
                        <?php foreach ($productos_destacados as $prod): ?>
                            <div class="product-card">
                                <div class="product-image">
                                    <i class="fas fa-image"></i>
                                </div>
                                <div class="product-info">
                                    <h3 class="product-name"><?php echo htmlspecialchars($prod['nombre']); ?></h3>
                                    <p class="product-description"><?php echo htmlspecialchars($prod['descripcion']); ?></p>
                                    <div class="product-price">$<?php echo number_format($prod['precio'], 0, ',', '.'); ?> COP</div>
                                    <div class="product-stock">Stock: <?php echo $prod['stock']; ?> unidades</div>
                                    <div class="product-badges">
                                        <span class="badge badge-warning">Destacado</span>
                                        <span class="badge badge-success">Disponible</span>
                                    </div>
                                    <div class="product-actions">
                                        <?php if (isset($_SESSION['user_id'])): ?>
                                            <form method="POST" action="public/carrito.php" style="display: inline;">
                                                <input type="hidden" name="action" value="add">
                                                <input type="hidden" name="producto_id" value="<?php echo $prod['id']; ?>">
                                                <input type="hidden" name="cantidad" value="1">
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fas fa-shopping-cart"></i> Agregar al Carrito
                                                </button>
                                            </form>
                                        <?php else: ?>
                                            <a href="public/login.php" class="btn btn-primary">
                                                <i class="fas fa-sign-in-alt"></i> Iniciar Sesión para Comprar
                                            </a>
                                        <?php endif; ?>
                                        <button class="btn btn-secondary">
                                            <i class="fas fa-heart"></i> Favorito
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <i class="fas fa-star"></i>
                        <h3>No hay productos destacados</h3>
                        <p>Pronto tendremos productos destacados disponibles</p>
                    </div>
                <?php endif; ?>

                <h2 class="section-title" style="margin-top: 3rem;">Productos Recientes</h2>
                <?php if (!empty($productos_recientes)): ?>
                    <div class="products-grid">
                        <?php foreach ($productos_recientes as $prod): ?>
                            <div class="product-card">
                                <div class="product-image">
                                    <i class="fas fa-image"></i>
                                </div>
                                <div class="product-info">
                                    <h3 class="product-name"><?php echo htmlspecialchars($prod['nombre']); ?></h3>
                                    <p class="product-description"><?php echo htmlspecialchars($prod['descripcion']); ?></p>
                                    <div class="product-price">$<?php echo number_format($prod['precio'], 0, ',', '.'); ?> COP</div>
                                    <div class="product-stock">Stock: <?php echo $prod['stock']; ?> unidades</div>
                                    <div class="product-badges">
                                        <?php if ($prod['destacado']): ?>
                                            <span class="badge badge-warning">Destacado</span>
                                        <?php endif; ?>
                                        <?php if ($prod['activo']): ?>
                                            <span class="badge badge-success">Disponible</span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="product-actions">
                                        <?php if (isset($_SESSION['user_id'])): ?>
                                            <form method="POST" action="public/carrito.php" style="display: inline;">
                                                <input type="hidden" name="action" value="add">
                                                <input type="hidden" name="producto_id" value="<?php echo $prod['id']; ?>">
                                                <input type="hidden" name="cantidad" value="1">
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fas fa-shopping-cart"></i> Agregar al Carrito
                                                </button>
                                            </form>
                                        <?php else: ?>
                                            <a href="public/login.php" class="btn btn-primary">
                                                <i class="fas fa-sign-in-alt"></i> Iniciar Sesión para Comprar
                                            </a>
                                        <?php endif; ?>
                                        <button class="btn btn-secondary">
                                            <i class="fas fa-heart"></i> Favorito
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <i class="fas fa-box-open"></i>
                        <h3>No hay productos disponibles</h3>
                        <p>Pronto tendremos productos disponibles en nuestra tienda</p>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </section>

    <footer class="footer">
        <div class="footer-content">
            <h3>Tienda Online</h3>
            <p>Tu tienda de confianza para productos de calidad</p>
            <div class="footer-links">
                <a href="#productos">Productos</a>
                <a href="#categorias">Categorías</a>
                <a href="admin/">Administración</a>
            </div>
            <p>&copy; 2024 Tienda Online. Todos los derechos reservados.</p>
        </div>
    </footer>
</body>
</html>
