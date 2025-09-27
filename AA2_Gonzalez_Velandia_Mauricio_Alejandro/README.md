# Sistema de Tienda Online - AA2

## Descripción del Proyecto

Sistema completo de tienda online desarrollado en PHP con MySQL, que incluye gestión de productos, usuarios, pedidos y carrito de compras.

## Características Principales

### Tienda Pública

- **Catálogo de productos** con filtros por categoría
- **Búsqueda avanzada** de productos
- **Carrito de compras** con validación de stock
- **Sistema de usuarios** con registro y login
- **Productos destacados** en página principal
- **Ocultación automática** de productos sin stock

### Panel de Administración

- **Gestión completa de productos** (CRUD)
- **Gestión de categorías** (CRUD)
- **Gestión de usuarios** (CRUD)
- **Gestión de pedidos** con estados
- **Estadísticas detalladas** de inventario
- **Control de stock** en tiempo real
- **Filtros avanzados** por estado y stock

### Sistema de Stock

- **Validación automática** de stock al agregar al carrito
- **Reducción automática** de stock al procesar pedidos
- **Ocultación de productos** sin stock en tienda pública
- **Alertas de stock bajo** en panel admin
- **Gestión completa** desde panel administrativo

## Tecnologías Utilizadas

- **Backend:** PHP 7.4+
- **Base de Datos:** MySQL 5.7+
- **Frontend:** HTML5, CSS3, JavaScript
- **Framework CSS:** Bootstrap 5
- **Iconos:** Font Awesome
- **Patrón:** MVC (Model-View-Controller)

## Estructura del Proyecto

```
AA2_Gonzalez_Velandia_Mauricio_Alejandro/
├── admin/                    # Panel de administración
│   ├── categorias.php       # Gestión de categorías
│   ├── configuracion.php    # Configuración del sistema
│   ├── index.php           # Dashboard principal
│   ├── login.php           # Login de administrador
│   ├── logout.php          # Cerrar sesión
│   ├── pedidos.php         # Gestión de pedidos
│   ├── productos.php       # Gestión de productos
│   └── usuarios.php        # Gestión de usuarios
├── config/
│   └── database.php        # Configuración de base de datos
├── database/
│   └── database_schema.sql # Esquema de base de datos
├── includes/
│   └── auth.php           # Sistema de autenticación
├── models/                # Modelos de datos
│   ├── Categoria.php      # Modelo de categorías
│   ├── Pedido.php        # Modelo de pedidos
│   ├── Producto.php      # Modelo de productos
│   └── Usuario.php       # Modelo de usuarios
├── public/               # Área pública
│   ├── carrito.php      # Carrito de compras
│   ├── images/          # Imágenes del sistema
│   ├── login.php        # Login de usuarios
│   ├── logout.php       # Cerrar sesión
│   ├── mi_cuenta.php    # Perfil de usuario
│   └── register.php     # Registro de usuarios
├── views/               # Vistas del sistema
│   └── admin/           # Vistas del panel admin
├── index.php           # Página principal
└── README.md          # Este archivo
```

## Instalación

### Requisitos Previos

- XAMPP o WAMP (Apache + MySQL + PHP)
- Navegador web moderno

### Pasos de Instalación

1. **Clonar/Descargar el proyecto**

   ```bash
   # Colocar en la carpeta htdocs de XAMPP
   C:\xampp\htdocs\AA2_Gonzalez_Velandia_Mauricio_Alejandro\
   ```

2. **Configurar Base de Datos**

   - Abrir phpMyAdmin
   - Crear nueva base de datos: `tienda_online_gonzalez`
   - Importar el archivo: `database/database_schema.sql`

3. **Configurar Conexión**

   - Editar `config/database.php`
   - Verificar credenciales de MySQL

4. **Iniciar Servicios**
   - Iniciar Apache y MySQL en XAMPP
   - Acceder a: `http://localhost/AA2_Gonzalez_Velandia_Mauricio_Alejandro/`

## Uso del Sistema

### Acceso Público

- **URL:** `http://localhost/AA2_Gonzalez_Velandia_Mauricio_Alejandro/`
- **Funciones:** Navegar productos, buscar, agregar al carrito, registrarse

### Panel de Administración

- **URL:** `http://localhost/AA2_Gonzalez_Velandia_Mauricio_Alejandro/admin/`
- **Usuario por defecto:** `admin`
- **Contraseña:** `admin123`

## Funcionalidades Detalladas

### Gestión de Productos

- Crear, editar, eliminar productos
- Subir imágenes de productos
- Control de stock en tiempo real
- Productos destacados
- Estados activo/inactivo
- Filtros por categoría, stock, estado

### Gestión de Stock

- Validación automática de stock
- Reducción automática al procesar pedidos
- Ocultación de productos sin stock
- Alertas de stock bajo
- Estadísticas detalladas de inventario

### Sistema de Pedidos

- Carrito de compras persistente
- Validación de stock antes de comprar
- Procesamiento con transacciones de BD
- Estados de pedidos (pendiente, procesado, completado)
- Detalles completos de cada pedido

### Gestión de Usuarios

- Registro de nuevos usuarios
- Sistema de login/logout
- Perfil de usuario
- Gestión desde panel admin

## Base de Datos

### Tablas Principales

- **usuarios:** Información de usuarios del sistema
- **categorias:** Categorías de productos
- **productos:** Catálogo de productos con stock
- **pedidos:** Pedidos realizados por usuarios
- **detalle_pedidos:** Detalles de productos en cada pedido

### Relaciones

- Usuarios → Pedidos (1:N)
- Categorías → Productos (1:N)
- Pedidos → Detalle_Pedidos (1:N)
- Productos → Detalle_Pedidos (1:N)

## Características Técnicas

### Seguridad

- Validación de entrada de datos
- Protección contra SQL Injection (PDO)
- Autenticación de usuarios
- Sesiones seguras

### Rendimiento

- Consultas optimizadas
- Índices en base de datos
- Caché de sesiones
- Transacciones para integridad

### Usabilidad

- Interfaz responsive
- Navegación intuitiva
- Mensajes de error claros
- Validación en tiempo real

## Autor

**Mauricio Alejandro González Velandia**

- Proyecto: AA2 - Desarrollo de Software Web Back-end
- Institución: Universidad Nacional de Colombia

## Licencia

Este proyecto es desarrollado con fines académicos y educativos.

---

_Sistema desarrollado con las mejores prácticas de programación web y arquitectura de software._
