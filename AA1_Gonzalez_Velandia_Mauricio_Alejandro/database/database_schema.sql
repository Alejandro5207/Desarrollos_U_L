CREATE DATABASE IF NOT EXISTS api_rest_db;
USE api_rest_db;

CREATE TABLE categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(200) NOT NULL,
    descripcion TEXT,
    precio DECIMAL(10,2) NOT NULL,
    stock INT DEFAULT 0,
    categoria_id INT,
    imagen_url VARCHAR(500),
    activo BOOLEAN DEFAULT TRUE,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (categoria_id) REFERENCES categorias(id) ON DELETE SET NULL
);

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    rol ENUM('admin', 'usuario') DEFAULT 'usuario',
    activo BOOLEAN DEFAULT TRUE,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO categorias (nombre, descripcion) VALUES
('Electrónicos', 'Dispositivos electrónicos y tecnología de última generación'),
('Ropa y Moda', 'Vestimenta, calzado y accesorios de moda'),
('Hogar y Jardín', 'Artículos para el hogar, decoración y jardinería'),
('Deportes y Fitness', 'Equipos deportivos, ropa deportiva y accesorios'),
('Libros y Educación', 'Libros, material educativo y cursos'),
('Salud y Belleza', 'Productos de cuidado personal y cosméticos'),
('Automotriz', 'Repuestos, accesorios y productos para vehículos'),
('Juguetes y Juegos', 'Juguetes, juegos de mesa y entretenimiento');

INSERT INTO productos (nombre, descripcion, precio, stock, categoria_id, imagen_url) VALUES
('Smartphone Samsung Galaxy S24', 'Teléfono inteligente con pantalla AMOLED de 6.2 pulgadas, cámara de 200MP y procesador Snapdragon 8 Gen 3', 4999999, 25, 1, 'https://example.com/samsung-galaxy-s24.jpg'),
('Laptop HP Pavilion 15', 'Laptop con procesador Intel Core i7 de 12va generación, 16GB RAM, 512GB SSD y pantalla Full HD de 15.6 pulgadas', 3499999, 15, 1, 'https://example.com/hp-pavilion-15.jpg'),
('Auriculares Sony WH-1000XM5', 'Auriculares inalámbricos con cancelación de ruido líder en la industria y 30 horas de batería', 1599999, 40, 1, 'https://example.com/sony-wh1000xm5.jpg'),
('Camiseta Nike Dri-FIT', 'Camiseta deportiva de algodón 100% con tecnología Dri-FIT para mantenerte seco durante el ejercicio', 119999, 100, 2, 'https://example.com/nike-dri-fit.jpg'),
('Zapatillas Adidas Ultraboost 22', 'Zapatillas de running con tecnología Boost para máxima comodidad y rendimiento', 749999, 60, 2, 'https://example.com/adidas-ultraboost.jpg'),
('Sofá 3 Plazas Moderno', 'Sofá moderno de tela gris con cojines desmontables y estructura de madera maciza', 2399999, 8, 3, 'https://example.com/sofa-moderno.jpg'),
('Mesa de Centro de Vidrio', 'Mesa de centro de vidrio templado con base de acero inoxidable, perfecta para salas modernas', 799999, 12, 3, 'https://example.com/mesa-centro-vidrio.jpg'),
('Balón de Fútbol Adidas', 'Balón oficial de fútbol FIFA con tecnología aerodinámica para máximo control y precisión', 199999, 75, 4, 'https://example.com/balon-adidas.jpg'),
('Mancuernas Ajustables', 'Set de mancuernas ajustables de 2.5kg a 25kg, perfectas para entrenamiento en casa', 599999, 20, 4, 'https://example.com/mancuernas-ajustables.jpg'),
('Libro "Programación en PHP 8"', 'Guía completa de programación en PHP 8 con ejemplos prácticos y mejores prácticas', 159999, 50, 5, 'https://example.com/libro-php8.jpg'),
('Curso Online de JavaScript', 'Curso completo de JavaScript moderno con proyectos reales y certificación incluida', 399999, 200, 5, 'https://example.com/curso-javascript.jpg'),
('Crema Hidratante Facial', 'Crema hidratante facial con ácido hialurónico y vitamina E para pieles secas y sensibles', 99999, 80, 6, 'https://example.com/crema-hidratante.jpg'),
('Aceite de Motor 5W-30', 'Aceite de motor sintético 5W-30 para vehículos de gasolina, 4 litros', 139999, 45, 7, 'https://example.com/aceite-motor.jpg'),
('Juego de Mesa Monopoly', 'Clásico juego de mesa Monopoly con tablero renovado y fichas de metal', 119999, 30, 8, 'https://example.com/monopoly.jpg'),
('Lego Creator 3en1', 'Set de construcción Lego Creator con 3 modelos diferentes para construir', 199999, 25, 8, 'https://example.com/lego-creator.jpg');

INSERT INTO usuarios (username, email, password_hash, nombre, apellido, rol) VALUES
('admin', 'admin@api.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrador', 'Sistema', 'admin'),
('usuario1', 'usuario1@api.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Juan', 'Pérez', 'usuario'),
('usuario2', 'usuario2@api.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'María', 'González', 'usuario');

CREATE INDEX idx_productos_categoria ON productos(categoria_id);
CREATE INDEX idx_productos_activo ON productos(activo);
CREATE INDEX idx_productos_nombre ON productos(nombre);
CREATE INDEX idx_productos_precio ON productos(precio);
CREATE INDEX idx_usuarios_username ON usuarios(username);
CREATE INDEX idx_usuarios_email ON usuarios(email);
CREATE INDEX idx_categorias_nombre ON categorias(nombre);