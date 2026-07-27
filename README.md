# Tazavera

Plataforma de verificación de café de especialidad (specialty coffee). Marketplace de dos lados donde especialistas y consumidores evalúan de forma independiente el café que ofrece cada cafetería, y el sistema deriva un **consenso** (promedio de lo que perciben los especialistas) para contrastarlo con lo que la cafetería declara vender.

Proyecto académico (bootcamp) con vocación de producto real. Construido en Laravel 13 + Livewire + Flux UI + Tailwind CSS v4 + MySQL 8.

## Documentación de diseño

Las decisiones de producto, el modelo de entidades, los casos de uso y las referencias del estándar SCA/CVA en el que se basa el sistema de evaluación viven **fuera de este repositorio**, en `Full Stack/Proyecto/`:

- `Idealizacion/proyecto-mvc-laravel-ideas.md` — definición del producto, MVP vs. backlog, fundamentación de las decisiones.
- `Desarrollo MVP/entidades.md` — esquema de base de datos y estructura real de los JSON (`descriptive`, `affective`, `consensus`).
- `Desarrollo MVP/notas_de_implementacion.md` — decisiones técnicas de implementación (motor de BD, indexación de JSON, lógica de derivados) y su estado real (qué está implementado, qué falta).
- `Desarrollo MVP/referencias.md` — material de referencia del estándar CVA/SCA y de concordancia inter-evaluador.
- `Desarrollo MVP/WCS_lexico.md` — léxico sensorial (taxonomía de descriptores) usado para poblar `olfactory_taxonomies`.
- `Casos de uso/casos de uso.md` — casos de uso del MVP, con nota de qué está implementado y qué no.
- `MER/` — diagrama entidad-relación (MySQL Workbench).

> Estos documentos no siempre reflejan el 100% del código en cada momento — ante una discrepancia, el código es la fuente de verdad; los documentos se actualizan cuando hay tiempo.

## Stack

- **Backend:** Laravel 13, PHP 8.3+ (probado con 8.5)
- **Frontend reactivo:** Livewire 4 (clases separadas, sin Volt) + Alpine.js
- **UI:** Flux UI (free tier)
- **Estilos:** Tailwind CSS v4
- **Base de datos:** MySQL 8 (**obligatorio** — ver nota abajo, no funciona con SQLite)
- **Build tool:** Vite

## Requisitos previos

- PHP 8.3 o superior, con las extensiones estándar de Laravel (`pdo_mysql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `bcmath`)
- Composer 2.x
- **MySQL 8** corriendo y accesible (standalone, Laravel Herd, Docker, XAMPP, etc. — cualquiera sirve mientras sea MySQL 8)
- Node.js 18+ y npm

## Instalación

### 1. Clonar el repositorio

```bash
git clone https://github.com/EdwinB1025/tazavera-app.git
cd tazavera-app
```

### 2. Instalar dependencias PHP

```bash
composer install
```

### 3. Configurar el entorno

```bash
cp .env.example .env
php artisan key:generate
```

**Importante:** `.env.example` trae `DB_CONNECTION=sqlite` por defecto (plantilla del starter kit de Laravel, sin ajustar a este proyecto). Hay que cambiarlo a MySQL a mano, porque una de las migraciones crea una vista SQL (`CREATE OR REPLACE VIEW`) que **no es válida en SQLite**. Editar `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tazavera
DB_USERNAME=root
DB_PASSWORD=
```

Crear la base de datos vacía en MySQL antes de migrar (con el cliente que uses, ej. `mysql -u root -p -e "CREATE DATABASE tazavera;"`).

### 4. Migrar y poblar la base de datos

```bash
php artisan migrate
php artisan db:seed
```

El seeder (`DatabaseSeeder`) corre, en orden: `CoffeshopSeeder`, `CoffeesSeeder`, `OlfactoryTaxonomySeeder` (113 nodos de la taxonomía sensorial desde CSV), `OfferingsSeeder`, `ProvisionalEvaluationSeeder`. Con esto queda una base de datos utilizable de punta a punta sin cargar nada a mano.

### 5. Instalar dependencias de frontend

```bash
npm install
```

## Levantar el proyecto

### Opción A — un solo comando (recomendado)

```bash
composer run dev
```

Levanta en paralelo: el servidor de Laravel (`php artisan serve`, puerto 8000), el worker de colas, los logs (`php artisan pail`) y Vite en modo desarrollo (hot reload). Todo en una sola terminal, con colores por proceso.

### Opción B — manual, en terminales separadas

```bash
php artisan serve
```

```bash
npm run dev
```

La primera levanta el backend en `http://localhost:8000`; la segunda compila y sirve los assets (CSS/JS) con recarga en caliente. Con solo `php artisan serve` corriendo (sin `npm run dev`), la app carga pero sin los estilos/JS compilados actualizados — hace falta al menos un `npm run build` una vez.

### Producción / build estático

```bash
npm run build
```

Compila los assets a `public/build/` una sola vez, sin proceso de desarrollo corriendo.

## Notas

- Login/registro los provee el starter kit de Livewire (autenticación nativa de Laravel) — no hace falta configurar nada extra para probarlos.
- `php artisan tinker` sirve para inspeccionar datos rápido (ej. `Evaluation::find(1)->descriptive`).
- Si algo falla al migrar con un error de sintaxis SQL, es casi seguro que `.env` sigue apuntando a `sqlite` — revisar el paso 3.
