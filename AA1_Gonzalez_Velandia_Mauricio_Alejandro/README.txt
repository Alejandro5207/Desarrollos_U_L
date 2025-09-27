================================================================================
                    PROYECTO API REST - PRODUCTOS
                    Estudiante: Mauricio Alejandro González Velandia
                    Curso: Desarrollo de Software Web Back-end
                    Fecha: 2025-01-27
================================================================================

DESCRIPCIÓN DEL PROYECTO
================================================================================

Este proyecto implementa una API REST completa para la gestión de productos,
desarrollada en PHP 8.0 con MySQL como base de datos. La API permite realizar
operaciones CRUD (Create, Read, Update, Delete) sobre una tabla de productos
con precios en pesos colombianos.

CARACTERÍSTICAS PRINCIPALES
================================================================================

- API REST completa con métodos GET, POST, PUT, DELETE
- Base de datos MySQL con 3 tablas (productos, categorías, usuarios)
- Interfaz web moderna y responsiva para pruebas
- Precios en pesos colombianos (COP)
- Validación de datos y manejo de errores
- CORS habilitado para peticiones cross-origin
- Documentación completa y pruebas automatizadas

TECNOLOGÍAS UTILIZADAS
================================================================================

Lenguaje de Programación: PHP 8.0
Base de Datos: MySQL 8.0
Servidor Web: Apache (XAMPP)
Frontend: HTML5, CSS3, JavaScript (ES6)
Herramientas: Visual Studio Code, Postman
Arquitectura: REST API, MVC Pattern

REQUISITOS DEL SISTEMA
================================================================================

- XAMPP 8.0 o superior
- PHP 8.0 o superior
- MySQL 8.0 o superior
- Apache 2.4 o superior
- Navegador web moderno (Chrome, Firefox, Edge)
- Postman (opcional, para pruebas de API)

INSTALACIÓN Y CONFIGURACIÓN
================================================================================

1. INSTALAR XAMPP
   - Descargar e instalar XAMPP desde https://www.apachefriends.org/
   - Iniciar los servicios Apache y MySQL desde el panel de control

2. CONFIGURAR EL PROYECTO
   - Copiar la carpeta del proyecto a: C:\xampp\htdocs\
   - La ruta final debe ser: C:\xampp\htdocs\AA1_Gonzalez_Velandia_Mauricio_Alejandro\

3. CONFIGURAR LA BASE DE DATOS
   - Abrir phpMyAdmin (http://localhost/phpmyadmin)
   - Crear nueva base de datos: "api_rest_db"
   - Importar el archivo: database/database_schema.sql
   - Verificar que se crearon las tablas: productos, categorías, usuarios

4. CONFIGURAR PERMISOS
   - Asegurar que Apache tenga permisos de lectura en la carpeta del proyecto
   - Verificar que el archivo .htaccess esté presente

5. PROBAR LA INSTALACIÓN
   - Abrir navegador y ir a: http://localhost/AA1_Gonzalez_Velandia_Mauricio_Alejandro/public/index.html
   - Verificar que la interfaz se carga correctamente
   - Probar las operaciones CRUD desde la interfaz

ESTRUCTURA DEL PROYECTO
================================================================================

AA1_Gonzalez_Velandia_Mauricio_Alejandro/
├── api/                          - Lógica de la API REST
│   ├── productos.php             - Endpoint principal de la API
│   ├── Producto.php              - Modelo de datos
│   └── ResponseHandler.php       - Manejador de respuestas
├── config/                       - Configuración
│   └── database.php              - Conexión a base de datos
├── database/                     - Scripts de base de datos
│   ├── database_schema.sql       - Estructura y datos iniciales
│   └── backup_database.sql       - Respaldo completo
├── docs/                         - Documentación
│   └── postman_collection.json   - Colección de Postman
├── public/                       - Interfaz web pública
│   └── index.html                - Interfaz de pruebas
├── tests/                        - Pruebas automatizadas
│   └── test_api.php              - Script de pruebas
├── .htaccess                     - Configuración de Apache
└── README.txt                    - Este archivo

ENDPOINTS DE LA API
================================================================================

Base URL: http://localhost/AA1_Gonzalez_Velandia_Mauricio_Alejandro/api/

GET    /productos.php              - Obtener todos los productos
GET    /productos.php?id=1         - Obtener producto por ID
GET    /productos.php?search=term  - Buscar productos
POST   /productos.php              - Crear nuevo producto
PUT    /productos.php              - Actualizar producto
DELETE /productos.php?id=1         - Eliminar producto

FORMATO DE RESPUESTAS
================================================================================

Todas las respuestas están en formato JSON con la siguiente estructura:

Éxito:
{
    "success": true,
    "message": "Mensaje descriptivo",
    "data": { ... }
}

Error:
{
    "success": false,
    "message": "Mensaje de error",
    "error": "Detalles del error"
}

PRUEBAS Y EVIDENCIAS
================================================================================

1. PRUEBAS AUTOMATIZADAS
   - Archivo: tests/test_api.php
   - URL: http://localhost/AA1_Gonzalez_Velandia_Mauricio_Alejandro/tests/test_api.php
   - Incluye pruebas para todos los métodos HTTP

2. INTERFAZ WEB
   - Archivo: public/index.html
   - URL: http://localhost/AA1_Gonzalez_Velandia_Mauricio_Alejandro/public/index.html
   - Interfaz moderna para probar la API

3. COLECCIÓN POSTMAN
   - Archivo: docs/postman_collection.json
   - Importar en Postman para pruebas avanzadas

DATOS DE PRUEBA
================================================================================

La base de datos incluye 15 productos de ejemplo con precios en pesos colombianos:

- Smartphone Samsung Galaxy S24: $4,999,999 COP
- Laptop HP Pavilion 15: $3,499,999 COP
- Auriculares Sony WH-1000XM5: $1,599,999 COP
- Y 12 productos más...

CONFIGURACIÓN AVANZADA
================================================================================

1. CORS: Configurado para permitir peticiones desde cualquier origen
2. Charset: UTF-8 para soporte completo de caracteres especiales
3. Errores: Mostrados en modo desarrollo para facilitar debugging
4. Seguridad: Archivos sensibles protegidos con .htaccess

SOLUCIÓN DE PROBLEMAS
================================================================================

1. Error de conexión a base de datos
   - Verificar que MySQL esté ejecutándose
   - Comprobar credenciales en config/database.php

2. Error 404 en la API
   - Verificar que mod_rewrite esté habilitado en Apache
   - Comprobar que el archivo .htaccess esté presente

3. Error de CORS
   - Verificar configuración en .htaccess
   - Comprobar headers en api/productos.php

4. Interfaz no carga
   - Verificar ruta correcta del proyecto
   - Comprobar permisos de Apache

CONTACTO Y SOPORTE
================================================================================

Desarrollador: Mauricio Alejandro González Velandia
Fecha de desarrollo: Enero 2025
Versión: 1.0.0

Para soporte técnico o consultas sobre el proyecto, contactar al desarrollador.

================================================================================
                    FIN DEL DOCUMENTO
================================================================================