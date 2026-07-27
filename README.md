# ☕ Tazavera

Plataforma de verificación de café de especialidad (specialty coffee). Marketplace de dos lados donde especialistas y consumidores evalúan de forma independiente el café que ofrece cada cafetería, y el sistema deriva un **consenso** (promedio de lo que perciben los especialistas) para contrastarlo con lo que la cafetería declara vender.

Proyecto académico (bootcamp) con vocación de producto real. Construido en Laravel 13 + Livewire + Flux UI + Tailwind CSS v4 + MySQL 8.

## 🕵️ El problema que ataca

Hay mucho café "specialty" que en realidad no lo es, y el consumidor de a pie no tiene cómo saberlo — la calificación queda en manos de pocos, y eso sesga el mercado. Tazavera no se conforma con un rating más: **verifica** lo que una cafetería dice vender. Si declara "notas frutales, acidez alta, proceso natural", un especialista cata el producto real y el sistema confirma o desmiente esa afirmación, contrastando la evaluación técnica (especialista) contra el gusto del consumidor y la declaración de la cafetería.

El sistema de evaluación se basa en el **SCA Coffee Value Assessment (CVA v2, standard provisional 2024-2025)**, adaptando el método de cupping formal a condiciones reales de cafetería (café ya preparado, sin réplica estricta del protocolo de tazas físicas) — el detalle de esa adaptación, con sus asunciones y debilidades metodológicas asumidas, vive en la documentación de diseño enlazada abajo.

## 📚 Documentación de diseño

Las decisiones de producto, el modelo de entidades, los casos de uso y las referencias del estándar SCA/CVA viven **fuera de este repositorio**, en `Full Stack/Proyecto/`:

- `Idealizacion/proyecto-mvc-laravel-ideas.md` — definición del producto, MVP vs. backlog, fundamentación de las decisiones.
- `Desarrollo MVP/entidades.md` — esquema de base de datos y estructura real de los JSON (`descriptive`, `affective`, `consensus`).
- `Desarrollo MVP/notas_de_implementacion.md` — decisiones técnicas de implementación (motor de BD, indexación de JSON, lógica de derivados) y su estado real (qué está implementado, qué falta).
- `Desarrollo MVP/referencias.md` — material de referencia del estándar CVA/SCA y de concordancia inter-evaluador.
- `Desarrollo MVP/WCS_lexico.md` — léxico sensorial (taxonomía de descriptores) usado para poblar `olfactory_taxonomies`.
- `Casos de uso/casos de uso.md` — casos de uso del MVP, con nota de qué está implementado y qué no.
- `MER/` — diagrama entidad-relación (MySQL Workbench).

> 🔄 Estos documentos no siempre reflejan el 100% del código en cada momento — ante una discrepancia, el código es la fuente de verdad; los documentos se actualizan cuando hay tiempo.

## 🧪 Modelo de evaluación (resumen)

Tres tipos de evaluación, separados por propósito y por tipo de evaluador (de los cuatro que define el CVA — el MVP usa tres):

- 🔬 **Descriptive (especialista)** — registro objetivo de intensidad, sin opinar si es rico o no. Siete ejes (fragancia, aroma, flavor, aftertaste, acidez, dulzor, mouthfeel) en escala 0-15, más descriptores CATA de una taxonomía jerárquica, Main Tastes y notas libres. En el MVP solo el eje `aroma` se captura como descriptivo puro (fragancia queda inactiva: no hay evaluación de molido seco, solo café preparado).
- ⭐ **Affective (especialista)** — el veredicto de calidad ("impression of quality"), mismos siete ejes + overall, escala 1-9. Deriva un **cupping score 0-100** por evaluación y un **consenso agregado** a nivel de offering (ver abajo). Incluye defectos sensoriales (`is_defective` + tipo orientativo no cerrado).
- 👤 **Consumer (rating simple)** — la reacción de "me gustó / no me gustó" homologada a la misma escala 1-9, con CATA restringido a los dos niveles superiores de la taxonomía. **Todavía no implementado** en código (el rol existe en el ENUM pero sin formulario ni validación propia).

Especialista y consumidor **no se promedian entre sí** — se contrastan atributo por atributo; ahí vive el valor de inteligencia de mercado. La distinción conceptual (calidad vs. gusto) se fundamenta en la separación CVA de "hardware" (mecánica de catar, se aprende rápido) vs. "software" (criterio calibrado por exposición, se aprende con el tiempo).

El esquema completo de entidades y la estructura real de los JSON (`descriptive`, `affective`, `consensus`) están en `Desarrollo MVP/entidades.md`.

## 🛠️ Stack

- 🐘 **Backend:** Laravel 13, PHP 8.3+ (probado con 8.5)
- ⚡ **Frontend reactivo:** Livewire 4 (clases separadas, sin Volt) + Alpine.js
- 🎨 **UI:** Flux UI (free tier)
- 💨 **Estilos:** Tailwind CSS v4
- 🗄️ **Base de datos:** MySQL 8 (**obligatorio** — ver nota abajo, no funciona con SQLite)
- 🌻 **Rueda de sabores:** D3.js v7 (sunburst zoomable, paleta propia — no la de la SCA, por copyright)
- 🗺️ **Mapa de locales:** Leaflet.js + tiles OpenStreetMap; búsqueda por proximidad vía Haversine en SQL (sin PostGIS)
- ⚙️ **Build tool:** Vite

**¿Por qué MySQL y no Postgres?** Decisión deliberada por el contexto del bootcamp (tiempo limitado, profundizar el SQL ya aprendido, evitar la curva de un motor nuevo), no por limitación técnica del proyecto. MySQL 8 cubre lo que el MVP necesita — tipo `json`, agregaciones y JSON indexado vía columnas generadas + multi-valued indexes — porque los bloques de evaluación se leen como unidad y solo se filtran unos pocos campos conocidos del catálogo (origen, variedad, proceso). Eloquent abstrae el motor, así que la sintaxis de consulta JSON es prácticamente idéntica si en el futuro se migra.

## ✅ Requisitos previos

- PHP 8.3 o superior, con las extensiones estándar de Laravel (`pdo_mysql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `bcmath`)
- Composer 2.x
- **MySQL 8** corriendo y accesible (standalone, Laravel Herd, Docker, XAMPP, etc. — cualquiera sirve mientras sea MySQL 8)
- Node.js 18+ y npm

## 🚀 Instalación

### 1. Clonar el repositorio

\`\`\`bash
git clone https://github.com/EdwinB1025/tazavera-app.git
cd tazavera-app
\`\`\`

### 2. Instalar dependencias PHP

\`\`\`bash
composer install
\`\`\`

### 3. Configurar el entorno

\`\`\`bash
cp .env.example .env
php artisan key:generate
\`\`\`

⚠️ **Importante:** \`.env.example\` trae \`DB_CONNECTION=sqlite\` por defecto (plantilla del starter kit de Laravel, sin ajustar a este proyecto). Hay que cambiarlo a MySQL a mano, porque una de las migraciones crea una vista SQL (\`CREATE OR REPLACE VIEW\`) que **no es válida en SQLite**. Editar \`.env\`:

\`\`\`env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tazavera
DB_USERNAME=root
DB_PASSWORD=
\`\`\`

Crear la base de datos vacía en MySQL antes de migrar (con el cliente que uses, ej. \`mysql -u root -p -e "CREATE DATABASE tazavera;"\`).

### 4. Migrar y poblar la base de datos

\`\`\`bash
php artisan migrate
php artisan db:seed
\`\`\`

🌱 El seeder (\`DatabaseSeeder\`) corre, en orden: \`CoffeshopSeeder\`, \`CoffeesSeeder\`, \`OlfactoryTaxonomySeeder\` (113 nodos de la taxonomía sensorial desde CSV, fuente: WCR Sensory Lexicon), \`OfferingsSeeder\`, \`ProvisionalEvaluationSeeder\`. Con esto queda una base de datos utilizable de punta a punta sin cargar nada a mano.

### 5. Instalar dependencias de frontend

\`\`\`bash
npm install
\`\`\`

## ▶️ Levantar el proyecto

### Opción A — un solo comando (recomendado)

\`\`\`bash
composer run dev
\`\`\`

Levanta en paralelo: el servidor de Laravel (\`php artisan serve\`, puerto 8000), el worker de colas, los logs (\`php artisan pail\`) y Vite en modo desarrollo (hot reload). Todo en una sola terminal, con colores por proceso.

### Opción B — manual, en terminales separadas

\`\`\`bash
php artisan serve
\`\`\`

\`\`\`bash
npm run dev
\`\`\`

La primera levanta el backend en \`http://localhost:8000\`; la segunda compila y sirve los assets (CSS/JS) con recarga en caliente. Con solo \`php artisan serve\` corriendo (sin \`npm run dev\`), la app carga pero sin los estilos/JS compilados actualizados — hace falta al menos un \`npm run build\` una vez.

### Producción / build estático

\`\`\`bash
npm run build
\`\`\`

Compila los assets a \`public/build/\` una sola vez, sin proceso de desarrollo corriendo.

## 📊 Estado real de implementación

El diseño (CVA/SCA) y el código no siempre van de la mano — esta tabla es la verdad sin filtro de qué está construido, para no asumir de más leyendo solo la documentación conceptual.

| Pieza | Estado |
|---|---|
| Evaluación descriptive (especialista) | ✅ Implementada (\`EvaluationForm\`, escala 0-15, CATA, main tastes) |
| Evaluación affective (especialista) | ✅ Implementada, incl. \`is_defective\` y cupping score individual |
| Cupping score individual (0-100) | ✅ Implementado — \`EvaluationController::computeCuppingScore()\` |
| Consenso agregado (\`offerings.consensus\`) | ✅ Implementado, se dispara solo al crear evaluación (\`store()\`), con >5 evaluaciones \`closed\`+\`specialist\` |
| Deducción \`-4d\` (defectos) en \`cupping_avg\` | ✅ Implementada, agregada a nivel de offering |
| Deducción \`-2u\` (uniformidad) | 📋 Backlog — no existe campo de uniformidad en el modelo aún |
| Recalcular consenso al **cerrar** vía \`update()\` | ⚠️ Gap conocido — solo se dispara desde \`store()\` |
| Concordancia inter-especialista (Kendall's W) | ❌ No implementada — columnas existen, sin lógica que las calcule |
| Evaluación consumer | ❌ No implementada — rol existe en el ENUM, sin formulario ni validación |
| \`specialist_profiles\` | 📋 Backlog — extensión 1-1 de \`users\` |

Detalle de cada gap, con su razón de diseño, en \`Desarrollo MVP/notas_de_implementacion.md\` (sección 7).

## 📝 Notas

- 🔐 Login/registro los provee el starter kit de Livewire (autenticación nativa de Laravel) — no hace falta configurar nada extra para probarlos.
- 🔍 \`php artisan tinker\` sirve para inspeccionar datos rápido (ej. \`Evaluation::find(1)->descriptive\`).
- 🩹 Si algo falla al migrar con un error de sintaxis SQL, es casi seguro que \`.env\` sigue apuntando a \`sqlite\` — revisar el paso 3.
- 🧩 Los campos JSON del modelo (\`descriptive\`, \`affective\`, \`consensus\`, \`extrinsics\`, \`attributes\`, \`contact\`) se leen como bloque y se parsean en backend; solo los campos de catálogo que se filtran de verdad (origen, variedad, proceso, tags) van a columnas generadas indexadas. Detalle de consultas en \`Desarrollo MVP/notas_de_implementacion.md\` (sección 4b).
