# Sistema de Inventario SOA — Backend

API REST para un sistema de inventario, desarrollado como primer avance del proyecto universitario de **Arquitectura Orientada a Servicios (SOA)**.

## Tecnologías

- **Laravel** 12.x
- **PHP** 8.2+
- **PostgreSQL** 17 (Docker)
- **API REST** (JSON)

## Requisitos Previos

- [PHP 8.2+](https://www.php.net/) con extensiones `pdo_pgsql` y `pgsql`
- [Composer](https://getcomposer.org/)
- [Docker Desktop](https://www.docker.com/products/docker-desktop/)

> Si usas XAMPP, PHP ya está disponible en `C:\xampp\php\php.exe` y Composer como `C:\xampp\php\composer.phar`.

## Instalación

### 1. Clonar el repositorio

```bash
git clone <URL_DEL_REPOSITORIO>
cd inventario-soa
```

### 2. Instalar dependencias

```bash
composer install
```

> Con XAMPP: `php C:\xampp\php\composer.phar install`

### 3. Configurar variables de entorno

```bash
cp .env.example .env
php artisan key:generate
```

Editar el archivo `.env` con los datos de tu base de datos:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=inventario_soa
DB_USERNAME=sail
DB_PASSWORD=password
```

### 4. Levantar PostgreSQL con Docker

```bash
docker compose up -d
```

Esto creará automáticamente la base de datos `inventario_soa` con PostgreSQL 17.

### 5. Ejecutar migraciones

```bash
php artisan migrate
```

### 6. Cargar datos de prueba

```bash
php artisan db:seed
```

Esto inserta 5 categorías y 10 productos de ejemplo.

### 7. Iniciar el servidor

```bash
php artisan serve
```

El servidor estará disponible en `http://127.0.0.1:8000`.

## Endpoints de la API

Base URL: `http://127.0.0.1:8000/api`

### Listar todos los productos

```
GET /api/productos
```

**Respuesta** (200):

```json
{
    "mensaje": "Lista de productos",
    "productos": [
        {
            "id": 1,
            "categoria_id": 1,
            "nombre": "Laptop HP Pavilion 15",
            "descripcion": "Laptop con procesador Intel Core i5, 8GB RAM, 256GB SSD.",
            "precio": "12999.99",
            "stock": 15,
            "created_at": "2026-07-10T20:19:28.000000Z",
            "updated_at": "2026-07-10T20:19:28.000000Z",
            "categoria": {
                "id": 1,
                "nombre": "Electrónica",
                "descripcion": "Dispositivos electrónicos, gadgets y accesorios tecnológicos."
            }
        }
    ]
}
```

### Obtener un producto por ID

```
GET /api/productos/{id}
```

**Respuesta** (200):

```json
{
    "mensaje": "Detalle del producto",
    "producto": {
        "id": 1,
        "categoria_id": 1,
        "nombre": "Laptop HP Pavilion 15",
        "descripcion": "...",
        "precio": "12999.99",
        "stock": 15,
        "categoria": { ... }
    }
}
```

**Respuesta** (404 — producto no encontrado):

```json
{
    "mensaje": "Producto no encontrado"
}
```

### Crear un producto

```
POST /api/productos
Content-Type: application/json
```

**Body:**

```json
{
    "categoria_id": 1,
    "nombre": "Monitor Samsung 27 4K",
    "descripcion": "Monitor UHD 4K IPS, 60Hz",
    "precio": 6999.99,
    "stock": 12
}
```

**Respuesta** (201):

```json
{
    "mensaje": "Producto creado exitosamente",
    "producto": { ... }
}
```

**Respuesta** (422 — errores de validación):

```json
{
    "message": "The categoria id field is required. (and 3 more errors)",
    "errors": {
        "categoria_id": ["The categoria id field is required."],
        "nombre": ["The nombre field is required."],
        "precio": ["The precio field must be at least 0."],
        "stock": ["The stock field must be an integer."]
    }
}
```

### Reglas de validación

| Campo          | Reglas                              |
|----------------|-------------------------------------|
| `categoria_id` | Requerido, debe existir en `categorias` |
| `nombre`       | Requerido, string, máximo 150 caracteres |
| `descripcion`  | Opcional, string                    |
| `precio`       | Requerido, numérico, mínimo 0      |
| `stock`        | Requerido, entero, mínimo 0        |

## Datos de Prueba

### Categorías (5)

| ID | Nombre       |
|----|--------------|
| 1  | Electrónica  |
| 2  | Ropa         |
| 3  | Alimentos    |
| 4  | Hogar        |
| 5  | Deportes     |

### Productos (10)

2 productos por cada categoría, incluyendo: Laptop HP, Audífonos Sony, Camiseta Nike, Jeans Levi's, Café Veracruz, Aceite de Oliva, Lámpara LED, Sábanas, Balón Adidas, Mancuernas.

## Estructura del Proyecto

```
inventario-soa/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       └── Api/
│   │           └── ProductoController.php    # Controlador de productos
│   └── Models/
│       ├── Categoria.php                     # Modelo Categoría
│       └── Producto.php                      # Modelo Producto
├── database/
│   ├── migrations/
│   │   ├── 2026_07_10_200001_create_categorias_table.php
│   │   └── 2026_07_10_200002_create_productos_table.php
│   └── seeders/
│       ├── CategoriaSeeder.php               # Datos de prueba: categorías
│       ├── ProductoSeeder.php                # Datos de prueba: productos
│       └── DatabaseSeeder.php                # Orquestador de seeders
├── routes/
│   └── api.php                               # Rutas de la API
├── docker-compose.yml                        # PostgreSQL 17 con Docker
├── .env                                      # Configuración del entorno
└── README.md                                 # Este archivo
```

## Comandos Útiles

```bash
# Revertir y volver a ejecutar migraciones + seeders
php artisan migrate:fresh --seed

# Verificar rutas registradas
php artisan route:list --path=api

# Detener el contenedor de PostgreSQL
docker compose down

# Detener y eliminar los datos
docker compose down -v
```

## Notas

- Este es el **primer avance** del proyecto. No incluye autenticación, cliente Android, reportes ni módulo de ventas.
- La API está diseñada para ser consumida posteriormente por una aplicación Android.
- Todas las respuestas son en formato **JSON**.
