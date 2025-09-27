CREATE DATABASE IF NOT EXISTS tienda_online_gonzalez;
USE tienda_online_gonzalez;

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    telefono VARCHAR(20),
    direccion TEXT,
    rol ENUM('admin', 'usuario') DEFAULT 'usuario',
    activo BOOLEAN DEFAULT TRUE,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    imagen VARCHAR(255),
    activo BOOLEAN DEFAULT TRUE,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(200) NOT NULL,
    descripcion TEXT,
    precio DECIMAL(10,2) NOT NULL,
    precio_oferta DECIMAL(10,2),
    stock INT DEFAULT 0,
    categoria_id INT,
    imagen_principal VARCHAR(255),
    galeria TEXT,
    destacado BOOLEAN DEFAULT FALSE,
    activo BOOLEAN DEFAULT TRUE,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (categoria_id) REFERENCES categorias(id) ON DELETE SET NULL
);

CREATE TABLE pedidos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    estado ENUM('pendiente', 'procesando', 'enviado', 'entregado', 'cancelado') DEFAULT 'pendiente',
    direccion_envio TEXT NOT NULL,
    telefono_contacto VARCHAR(20),
    notas TEXT,
    fecha_pedido TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

CREATE TABLE detalle_pedidos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pedido_id INT NOT NULL,
    producto_id INT NOT NULL,
    cantidad INT NOT NULL,
    precio_unitario DECIMAL(10,2) NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (pedido_id) REFERENCES pedidos(id) ON DELETE CASCADE,
    FOREIGN KEY (producto_id) REFERENCES productos(id) ON DELETE CASCADE
);

CREATE TABLE configuracion (
    id INT AUTO_INCREMENT PRIMARY KEY,
    clave VARCHAR(100) UNIQUE NOT NULL,
    valor TEXT,
    descripcion TEXT,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO usuarios (nombre, apellido, email, password, telefono, direccion, rol) VALUES
('admin', 'Administrador', 'admin@tienda.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '3001234567', 'Calle 123 #45-67, Bogotá', 'admin'),
('Mauricio', 'González', 'mauricio@tienda.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '3001234567', 'Calle 123 #45-67, Bogotá', 'admin'),
('Juan', 'Pérez', 'juan@tienda.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '3002345678', 'Carrera 45 #78-90, Medellín', 'usuario'),
('María', 'García', 'maria@tienda.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '3003456789', 'Avenida 67 #12-34, Cali', 'usuario');

INSERT INTO categorias (nombre, descripcion, imagen) VALUES
('Electrónicos', 'Dispositivos electrónicos y tecnología de última generación', 'https://images.unsplash.com/photo-1498049794561-7780e7231661?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80'),
('Ropa y Moda', 'Vestimenta, calzado y accesorios de moda para todas las edades', 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80'),
('Hogar y Jardín', 'Artículos para el hogar, decoración y jardinería', 'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80'),
('Deportes y Fitness', 'Equipos deportivos, ropa deportiva y accesorios', 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80'),
('Libros y Educación', 'Libros, material educativo y cursos online', 'https://images.unsplash.com/photo-1481627834876-b7833e8f5570?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80'),
('Salud y Belleza', 'Productos de cuidado personal y cosméticos', 'https://images.unsplash.com/photo-1596462502278-27bfdc403348?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80'),
('Automotriz', 'Repuestos, accesorios y productos para vehículos', 'https://images.unsplash.com/photo-1492144534655-ae79c964c9d7?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80'),
('Juguetes y Juegos', 'Juguetes, juegos de mesa y entretenimiento', 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80');

INSERT INTO productos (nombre, descripcion, precio, precio_oferta, stock, categoria_id, imagen_principal, destacado) VALUES
('Smartphone Samsung Galaxy S24', 'Teléfono inteligente con pantalla AMOLED de 6.2 pulgadas, cámara de 200MP y procesador Snapdragon 8 Gen 3', 4999999, 4499999, 25, 1, 'https://images.samsung.com/is/image/samsung/p6pim/latin/2401/gallery/latin-galaxy-s24-s928-sm-s928bzkdltc-539864180?$650_519_PNG$', TRUE),
('Laptop HP Pavilion 15', 'Laptop con procesador Intel Core i7 de 12va generación, 16GB RAM, 512GB SSD y pantalla Full HD de 15.6 pulgadas', 3499999, 3199999, 15, 1, 'https://ssl-product-images.www8-hp.com/digmedialib/prodimg/lowres/c07962448.png', TRUE),
('Auriculares Sony WH-1000XM5', 'Auriculares inalámbricos con cancelación de ruido líder en la industria y 30 horas de batería', 1599999, 1399999, 40, 1, 'https://www.sony.com/image/4a0b4b4b4b4b4b4b4b4b4b4b4b4b4b4b', FALSE),
('iPhone 15 Pro Max', 'Smartphone Apple con chip A17 Pro, cámara de 48MP y pantalla Super Retina XDR de 6.7 pulgadas', 5999999, 5499999, 20, 1, 'https://store.storeimages.cdn-apple.com/4982/as-images.apple.com/is/iphone-15-pro-finish-select-202309-6-1inch-naturaltitanium?wid=2560&hei=1440&fmt=p-jpg&qlt=80&.v=1693009279823', TRUE),
('Tablet iPad Air', 'Tablet Apple con chip M1, pantalla Liquid Retina de 10.9 pulgadas y soporte para Apple Pencil', 2999999, 2799999, 30, 1, 'https://store.storeimages.cdn-apple.com/4982/as-images.apple.com/is/ipad-air-select-wifi-blue-202203?wid=2560&hei=1440&fmt=p-jpg&qlt=80&.v=1645065732683', FALSE),
('Smartwatch Apple Watch Series 9', 'Reloj inteligente con GPS, monitor de salud avanzado y resistencia al agua hasta 50 metros', 1999999, 1799999, 35, 1, 'https://store.storeimages.cdn-apple.com/4982/as-images.apple.com/is/watch-s9-select-aluminum-pink-202309?wid=2560&hei=1440&fmt=p-jpg&qlt=80&.v=1693009279823', TRUE),
('Camiseta Nike Dri-FIT', 'Camiseta deportiva de algodón 100% con tecnología Dri-FIT para mantenerte seco durante el ejercicio', 119999, 99999, 100, 2, 'https://static.nike.com/a/images/t_PDP_1728_v1/f_auto,q_auto:eco/b7d9211c-26e7-431a-ac24-b0540fb3c00f/dri-fit-adv-mens-training-tank-6KJZJd.png', FALSE),
('Zapatillas Adidas Ultraboost 22', 'Zapatillas de running con tecnología Boost para máxima comodidad y rendimiento', 749999, 699999, 60, 2, 'https://assets.adidas.com/images/h_840,f_auto,q_auto,fl_lossy,c_fill,g_auto/fbaf991a2bc14dce5aababed016f27f2_9366/Ultraboost_22_Shoes_Black_GZ0127_01_standard.jpg', TRUE),
('Jeans Levis 501', 'Jeans clásicos de mezclilla 100% algodón con corte regular y lavado vintage', 299999, 249999, 80, 2, 'https://www.levi.com/dw/image/v2/BBGP_PRD/on/demandware.static/-/Sites-levi-master-catalog/default/dw12345678/images/products/501/019110000_1_1.jpg', FALSE),
('Chaqueta North Face', 'Chaqueta impermeable para clima frío con tecnología DryVent y capucha ajustable', 899999, 799999, 25, 2, 'https://www.thenorthface.com/dw/image/v2/BDGP_PRD/on/demandware.static/-/Sites-tnf-master-catalog/default/dw12345678/images/products/NF0A2Y5J_1_1.jpg', FALSE),
('Sofá 3 Plazas Moderno', 'Sofá moderno de tela gris con cojines desmontables y estructura de madera maciza', 2399999, 2199999, 8, 3, 'https://www.ikea.com/images/sofa-kivik-3-seat-cover-gunnared-medium-gray__0737165_pe740136_s5.jpg', FALSE),
('Mesa de Centro de Vidrio', 'Mesa de centro de vidrio templado con base de acero inoxidable, perfecta para salas modernas', 799999, 699999, 12, 3, 'https://www.wayfair.com/images/products/glass-coffee-table-modern-living-room-furniture__12345678.jpg', FALSE),
('Cama King Size', 'Cama king size con cabecera tapizada, colchón incluido y estructura de madera maciza', 1899999, 1699999, 10, 3, 'https://www.westelm.com/images/products/king-bed-frame-upholstered-headboard__12345678.jpg', FALSE),
('Refrigerador Samsung', 'Refrigerador de 500L con tecnología Twin Cooling Plus y dispensador de agua', 3299999, 2999999, 8, 3, 'https://images.samsung.com/is/image/samsung/p6pim/latin/2101/gallery/latin-rf28k9070sg-rf28k9070sg-539864180?$650_519_PNG$', TRUE),
('Balón de Fútbol Adidas', 'Balón oficial de fútbol FIFA con tecnología aerodinámica para máximo control y precisión', 199999, 179999, 75, 4, 'https://assets.adidas.com/images/h_840,f_auto,q_auto,fl_lossy,c_fill,g_auto/fbaf991a2bc14dce5aababed016f27f2_9366/Al_Rihla_Pro_Soccer_Ball_White_H57739_01_standard.jpg', FALSE),
('Mancuernas Ajustables', 'Set de mancuernas ajustables de 2.5kg a 25kg, perfectas para entrenamiento en casa', 599999, 549999, 20, 4, 'https://www.bowflex.com/images/products/adjustable-dumbbells-552__12345678.jpg', FALSE),
('Bicicleta Trek Mountain', 'Bicicleta de montaña con cuadro de aluminio, suspensión delantera y 21 velocidades', 1599999, 1399999, 15, 4, 'https://www.trekbikes.com/images/products/marlin-7-mountain-bike__12345678.jpg', FALSE),
('Cinta de Correr ProForm', 'Cinta de correr eléctrica con motor de 3.0 HP, pantalla LCD y programas de entrenamiento', 2299999, 1999999, 12, 4, 'https://www.proform.com/images/products/treadmill-9000__12345678.jpg', FALSE),
('Libro "Programación en PHP 8"', 'Guía completa de programación en PHP 8 con ejemplos prácticos y mejores prácticas', 159999, 139999, 50, 5, 'https://images-na.ssl-images-amazon.com/images/I/51W1sBPO7tL._SX342_SY445_.jpg', FALSE),
('Curso Online de JavaScript', 'Curso completo de JavaScript moderno con proyectos reales y certificación incluida', 399999, 349999, 200, 5, 'https://www.udemy.com/images/course/750x422/12345678.jpg', TRUE),
('Libro "Clean Code"', 'Guía esencial para escribir código limpio y mantenible por Robert C. Martin', 129999, 109999, 60, 5, 'https://images-na.ssl-images-amazon.com/images/I/41xShlnTZTL._SX376_BO1,204,203,200_.jpg', TRUE),
('Crema Hidratante Facial', 'Crema hidratante facial con ácido hialurónico y vitamina E para pieles secas y sensibles', 99999, 89999, 80, 6, 'https://www.sephora.com/images/products/moisturizer-hyaluronic-acid__12345678.jpg', FALSE),
('Kit de Maquillaje Profesional', 'Kit completo de maquillaje con 24 sombras, base, corrector y brochas profesionales', 399999, 349999, 40, 6, 'https://www.maccosmetics.com/images/products/makeup-kit-professional__12345678.jpg', FALSE),
('Aceite de Motor 5W-30', 'Aceite de motor sintético 5W-30 para vehículos de gasolina, 4 litros', 139999, 119999, 45, 7, 'https://www.mobil.com/images/products/motor-oil-5w30__12345678.jpg', FALSE),
('Neumáticos Michelin', 'Set de 4 neumáticos Michelin Energy Saver 205/55R16 para automóviles', 899999, 799999, 20, 7, 'https://www.michelin.com/images/products/tires-energy-saver__12345678.jpg', FALSE),
('Juego de Mesa Monopoly', 'Clásico juego de mesa Monopoly con tablero renovado y fichas de metal', 119999, 99999, 30, 8, 'https://www.hasbro.com/images/products/monopoly-classic__12345678.jpg', FALSE),
('Lego Creator 3en1', 'Set de construcción Lego Creator con 3 modelos diferentes para construir', 199999, 179999, 25, 8, 'https://www.lego.com/images/products/creator-3in1__12345678.jpg', FALSE),
('Juego PlayStation 5', 'Consola de videojuegos PlayStation 5 con SSD de 825GB y control DualSense', 2499999, 2299999, 18, 8, 'https://gmedia.playstation.com/is/image/SIEPDC/ps5-product-thumbnail-01-en-14sep21?$facebook$', TRUE),
('Puzzle 1000 Piezas', 'Puzzle de 1000 piezas con imagen de paisaje montañoso, perfecto para relajarse', 89999, 79999, 50, 8, 'https://www.ravensburger.com/images/products/puzzle-1000-pieces__12345678.jpg', FALSE);

INSERT INTO configuracion (clave, valor, descripcion) VALUES
('nombre_tienda', 'Tienda Online González', 'Nombre de la tienda'),
('email_contacto', 'contacto@tienda.com', 'Email de contacto principal'),
('telefono_contacto', '3001234567', 'Teléfono de contacto'),
('direccion_tienda', 'Calle 123 #45-67, Bogotá, Colombia', 'Dirección física de la tienda'),
('moneda', 'COP', 'Moneda utilizada'),
('impuestos', '19', 'Porcentaje de IVA'),
('envio_gratis_desde', '500000', 'Monto mínimo para envío gratis'),
('costo_envio', '15000', 'Costo fijo de envío');

CREATE INDEX idx_productos_categoria ON productos(categoria_id);
CREATE INDEX idx_productos_activo ON productos(activo);
CREATE INDEX idx_productos_destacado ON productos(destacado);
CREATE INDEX idx_productos_nombre ON productos(nombre);
CREATE INDEX idx_usuarios_email ON usuarios(email);
CREATE INDEX idx_pedidos_usuario ON pedidos(usuario_id);
CREATE INDEX idx_pedidos_estado ON pedidos(estado);
CREATE INDEX idx_detalle_pedido ON detalle_pedidos(pedido_id);
