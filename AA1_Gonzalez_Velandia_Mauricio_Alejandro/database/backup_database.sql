CREATE DATABASE IF NOT EXISTS `api_rest_db` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `api_rest_db`;

DROP TABLE IF EXISTS `categorias`;
CREATE TABLE `categorias` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text,
  `fecha_creacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_actualizacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `productos`;
CREATE TABLE `productos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(200) NOT NULL,
  `descripcion` text,
  `precio` decimal(10,2) NOT NULL,
  `stock` int DEFAULT '0',
  `categoria_id` int DEFAULT NULL,
  `imagen_url` varchar(500) DEFAULT NULL,
  `activo` tinyint(1) DEFAULT '1',
  `fecha_creacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_actualizacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_productos_categoria` (`categoria_id`),
  KEY `idx_productos_activo` (`activo`),
  KEY `idx_productos_nombre` (`nombre`),
  KEY `idx_productos_precio` (`precio`),
  CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE `usuarios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `rol` enum('admin','usuario') DEFAULT 'usuario',
  `activo` tinyint(1) DEFAULT '1',
  `fecha_creacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_actualizacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  KEY `idx_usuarios_username` (`username`),
  KEY `idx_usuarios_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `categorias` WRITE;
INSERT INTO `categorias` (`id`, `nombre`, `descripcion`, `fecha_creacion`, `fecha_actualizacion`) VALUES
(1,'Electrónicos','Dispositivos electrónicos y tecnología de última generación','2025-01-27 11:15:00','2025-01-27 11:15:00'),
(2,'Ropa y Moda','Vestimenta, calzado y accesorios de moda','2025-01-27 11:15:00','2025-01-27 11:15:00'),
(3,'Hogar y Jardín','Artículos para el hogar, decoración y jardinería','2025-01-27 11:15:00','2025-01-27 11:15:00'),
(4,'Deportes y Fitness','Equipos deportivos, ropa deportiva y accesorios','2025-01-27 11:15:00','2025-01-27 11:15:00'),
(5,'Libros y Educación','Libros, material educativo y cursos','2025-01-27 11:15:00','2025-01-27 11:15:00'),
(6,'Salud y Belleza','Productos de cuidado personal y cosméticos','2025-01-27 11:15:00','2025-01-27 11:15:00'),
(7,'Automotriz','Repuestos, accesorios y productos para vehículos','2025-01-27 11:15:00','2025-01-27 11:15:00'),
(8,'Juguetes y Juegos','Juguetes, juegos de mesa y entretenimiento','2025-01-27 11:15:00','2025-01-27 11:15:00');
UNLOCK TABLES;

LOCK TABLES `productos` WRITE;
INSERT INTO `productos` (`id`, `nombre`, `descripcion`, `precio`, `stock`, `categoria_id`, `imagen_url`, `activo`, `fecha_creacion`, `fecha_actualizacion`) VALUES
(1,'Smartphone Samsung Galaxy S24','Teléfono inteligente con pantalla AMOLED de 6.2 pulgadas, cámara de 200MP y procesador Snapdragon 8 Gen 3',4999999,25,1,'https://example.com/samsung-galaxy-s24.jpg',1,'2025-01-27 11:15:00','2025-01-27 11:15:00'),
(2,'Laptop HP Pavilion 15','Laptop con procesador Intel Core i7 de 12va generación, 16GB RAM, 512GB SSD y pantalla Full HD de 15.6 pulgadas',3499999,15,1,'https://example.com/hp-pavilion-15.jpg',1,'2025-01-27 11:15:00','2025-01-27 11:15:00'),
(3,'Auriculares Sony WH-1000XM5','Auriculares inalámbricos con cancelación de ruido líder en la industria y 30 horas de batería',1599999,40,1,'https://example.com/sony-wh1000xm5.jpg',1,'2025-01-27 11:15:00','2025-01-27 11:15:00'),
(4,'Camiseta Nike Dri-FIT','Camiseta deportiva de algodón 100% con tecnología Dri-FIT para mantenerte seco durante el ejercicio',119999,100,2,'https://example.com/nike-dri-fit.jpg',1,'2025-01-27 11:15:00','2025-01-27 11:15:00'),
(5,'Zapatillas Adidas Ultraboost 22','Zapatillas de running con tecnología Boost para máxima comodidad y rendimiento',749999,60,2,'https://example.com/adidas-ultraboost.jpg',1,'2025-01-27 11:15:00','2025-01-27 11:15:00'),
(6,'Sofá 3 Plazas Moderno','Sofá moderno de tela gris con cojines desmontables y estructura de madera maciza',2399999,8,3,'https://example.com/sofa-moderno.jpg',1,'2025-01-27 11:15:00','2025-01-27 11:15:00'),
(7,'Mesa de Centro de Vidrio','Mesa de centro de vidrio templado con base de acero inoxidable, perfecta para salas modernas',799999,12,3,'https://example.com/mesa-centro-vidrio.jpg',1,'2025-01-27 11:15:00','2025-01-27 11:15:00'),
(8,'Balón de Fútbol Adidas','Balón oficial de fútbol FIFA con tecnología aerodinámica para máximo control y precisión',199999,75,4,'https://example.com/balon-adidas.jpg',1,'2025-01-27 11:15:00','2025-01-27 11:15:00'),
(9,'Mancuernas Ajustables','Set de mancuernas ajustables de 2.5kg a 25kg, perfectas para entrenamiento en casa',599999,20,4,'https://example.com/mancuernas-ajustables.jpg',1,'2025-01-27 11:15:00','2025-01-27 11:15:00'),
(10,'Libro "Programación en PHP 8"','Guía completa de programación en PHP 8 con ejemplos prácticos y mejores prácticas',159999,50,5,'https://example.com/libro-php8.jpg',1,'2025-01-27 11:15:00','2025-01-27 11:15:00'),
(11,'Curso Online de JavaScript','Curso completo de JavaScript moderno con proyectos reales y certificación incluida',399999,200,5,'https://example.com/curso-javascript.jpg',1,'2025-01-27 11:15:00','2025-01-27 11:15:00'),
(12,'Crema Hidratante Facial','Crema hidratante facial con ácido hialurónico y vitamina E para pieles secas y sensibles',99999,80,6,'https://example.com/crema-hidratante.jpg',1,'2025-01-27 11:15:00','2025-01-27 11:15:00'),
(13,'Aceite de Motor 5W-30','Aceite de motor sintético 5W-30 para vehículos de gasolina, 4 litros',139999,45,7,'https://example.com/aceite-motor.jpg',1,'2025-01-27 11:15:00','2025-01-27 11:15:00'),
(14,'Juego de Mesa Monopoly','Clásico juego de mesa Monopoly con tablero renovado y fichas de metal',119999,30,8,'https://example.com/monopoly.jpg',1,'2025-01-27 11:15:00','2025-01-27 11:15:00'),
(15,'Lego Creator 3en1','Set de construcción Lego Creator con 3 modelos diferentes para construir',199999,25,8,'https://example.com/lego-creator.jpg',1,'2025-01-27 11:15:00','2025-01-27 11:15:00');
UNLOCK TABLES;

LOCK TABLES `usuarios` WRITE;
INSERT INTO `usuarios` (`id`, `username`, `email`, `password_hash`, `nombre`, `apellido`, `rol`, `activo`, `fecha_creacion`, `fecha_actualizacion`) VALUES
(1,'admin','admin@api.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','Administrador','Sistema','admin',1,'2025-01-27 11:15:00','2025-01-27 11:15:00'),
(2,'usuario1','usuario1@api.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','Juan','Pérez','usuario',1,'2025-01-27 11:15:00','2025-01-27 11:15:00'),
(3,'usuario2','usuario2@api.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','María','González','usuario',1,'2025-01-27 11:15:00','2025-01-27 11:15:00');
UNLOCK TABLES;