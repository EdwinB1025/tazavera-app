# Notas de implementación

> Registro operativo del **cómo construir** (separado del *qué*, que vive en `proyecto-mvc-laravel-ideas.md` y `modelo-entidades.md`). El diseño dice qué es el sistema; estas notas dicen cómo montarlo. Cambian según el entorno.

---

## 1. Setup de entorno

### Base de datos
- **Motor:** **MySQL 8** (decisión final). Se eligió sobre PostgreSQL por el contexto del bootcamp (tiempo limitado, profundizar el aprendizaje de SQL ya iniciado, evitar la curva de un motor nuevo). MySQL 8 cubre lo que el MVP necesita: tipo `json`, agregaciones (AVG, STDDEV, GROUP BY) e indexación de JSON vía columnas generadas. La pérdida frente a Postgres (índices GIN sobre jsonb, filtrado flexible sobre JSON arbitrario) no afecta el MVP, donde los ejes se leen como bloque y solo se filtran pocos campos conocidos del catálogo.
- **MySQL es obligatorio, no opcional:** la migración `create_cata_attributes_view.php` usa `CREATE OR REPLACE VIEW` (sintaxis MySQL, no válida en SQLite). **`.env.example` del repo trae `DB_CONNECTION=sqlite` por defecto** (plantilla del starter kit de Laravel, sin ajustar) — hay que cambiarlo a `mysql` a mano antes de migrar, o esa migración falla. Ver README del repo para el detalle de configuración.
- **Driver PHP:** `pdo_mysql`.
- **Configuración Laravel:** `.env` con `DB_CONNECTION=mysql` + credenciales. Vía Eloquent el motor queda abstraído.

### PHP
- **Versión activa (CLI): PHP 8.5.4 standalone**, no la de XAMPP. El PATH de Windows apunta a la instalación standalone, por eso `php --version` en la terminal da 8.5.4. Xdebug 3.5.1 cargado.
- **Requisito Laravel 13:** PHP 8.3 mínimo (soporta hasta 8.5). El 8.5.4 cumple de sobra.
- **Nota de entorno:** Laravel se sirve con `php artisan serve`, que usa el PHP de la **terminal** (8.5.4) — no depende de ningún Apache. Si en algún momento un comando falla raro, verificar con `where php` que se usa el 8.5.4 y no se coló otra instalación.

### Configuración de arranque de Laravel (elegida en el instalador)
- **Versión:** Laravel 13 (lanzado 17 mar 2026; requiere PHP 8.3+). Para un proyecto nuevo es la versión moderna por defecto, sin fricción; Livewire/Inertia/Filament/Spatie ya son compatibles.
- **Starter kit:** **Livewire** — para no montar la autenticación a mano (registro, login, recuperación de contraseña, verificación de email vienen hechos) y enfocar el esfuerzo en las funcionalidades de verificación. Aporta reactividad en PHP/Blade sin escribir JavaScript.
- **Authentication provider:** **Laravel** (sistema nativo: tablas `users`, sesiones, guard estándar). La capa de roles (consumer/specialist/coffeeshop) se construye encima.
- **Single-file Livewire components:** **No** — clase PHP y vista Blade separadas. Mejor para aprender (separa lógica de presentación) y coincide con el grueso de la documentación/ejemplos.
- **Testing framework:** **PHPUnit** — el estándar ya conocido del bootcamp (data providers, `expectException`, etc.). Pest (sobre PHPUnit) queda como opción futura.
- **Laravel Boost (asistencia IA):** **No** — se prioriza aprender a pulso (docs + prueba y error). Instalable después si se quiere acelerar con la base ya sólida.

### Tablas que aporta el framework (no van en el modelo de dominio)
El sistema de auth de Laravel/Livewire genera tablas técnicas estándar que **no** se documentan en `modelo-entidades.md` (son infraestructura del framework, no dominio): `users` (se ajusta para añadir `role_id` y los campos del modelo), `password_reset_tokens`, `sessions`, y `personal_access_tokens` solo si se usa API con Sanctum. Confirmar que `users` tenga `password` y `remember_token` (campos estándar).

### Primeros pasos al arrancar
1. Configurar `.env` con la conexión MySQL (cuando se decida dónde corre).
2. Ajustar la migración de `users` (añadir `role_id`, campos del modelo) y crear la tabla `roles`.
3. `php artisan migrate`.
4. `php artisan serve` y comprobar que registro/login funcionan.

---

## 2. Generación de la base de datos

- **Las tablas se crean vía migraciones de Laravel**, no SQL manual. El schema builder traduce al dialecto de MySQL. No se necesita escribir DDL a mano para lo básico.
- **Columnas JSON:** usar `$table->json('campo')` (tipo `json` de MySQL 8). Campos JSON del modelo: `locations.contact`, `roasteries.contact`, `coffees.extrinsics`, `products.attributes`, `offerings.consensus`, y los bloques completos `evaluations.descriptive` y `evaluations.affective` (que embeben axis, cata, main_tastes, defect_types). La estructura de cada JSON está documentada con ejemplos en `modelo-entidades.md`.
- **Indexación de JSON (MySQL no indexa columnas JSON directamente):** dos vías, ninguna con SQL exótico:
  - **Columnas generadas (recomendado):** extraer el campo JSON que se filtra a una columna virtual/almacenada e indexarla. En migración: `$table->string('origin')->storedAs("extrinsics->>'$.origin'")` + `$table->index('origin')`; o `DB::statement('ALTER TABLE coffee ADD COLUMN origin VARCHAR(100) GENERATED ALWAYS AS (extrinsics->>"$.origin") STORED, ADD INDEX(origin)')`. Solo para los pocos campos que se filtran (catálogo: origen, variedad, proceso).
  - **Multi-valued indexes (MySQL 8.0.17+):** para arrays dentro del JSON (tags, descriptores), permite `MEMBER OF()`, `JSON_CONTAINS()`, `JSON_OVERLAPS()` sin escaneo completo. Sintaxis: `ADD INDEX idx ((CAST(attributes->'$.tags' AS CHAR(50) ARRAY)))`.
  - Nota: los **rangos** (precio) van en columna normal con índice B-tree, no en JSON.
- **Axis y cata como JSON (no tablas):** los ejes (incl. acidity), las catas y main_tastes viven embebidos en los JSON `descriptive`/`affective` de `evaluations`. No son tablas ni relaciones — se leen como bloque y se parsean en backend para el cálculo. No hay relación polimórfica ni tabla `axis`/`cata`.
- **Árbol auto-referencial:** `olfactory_taxonomies` con `parent_id` como FK a sí misma (mismo tipo BIGINT UNSIGNED en ambos lados); trivial en migraciones.
- **Vistas para atributos CATA del formulario.** MySQL 8 soporta vistas (`VIEW`) — consulta guardada que se comporta como tabla virtual. (No tiene `MATERIALIZED VIEW` nativa como Postgres; si se quisiera cachear se emula con una tabla refrescada por proceso, pero para el MVP la vista normal basta.) Decisión de diseño: los descriptores disponibles por dimensión del formulario se exponen vía **vista** que filtra `olfactory_taxonomy` por nivel/dimensión (general → nivel ≤ 1), evitando una tabla de configuración duplicada. Notas:
  - Laravel **no** gestiona vistas con el schema builder estándar: se crean con SQL crudo en una migración (`DB::statement('CREATE VIEW ...')`).
  - Un modelo Eloquent puede **leer** de la vista como si fuera tabla (solo lectura; es una vista con UNION, no actualizable).
  - **Estado real:** la vista y el modelo (`App\Models\CataAttribute`) están creados, pero el formulario de evaluación terminó **sin usarla** — consulta `olfactory_taxonomies` directo vía scopes de Eloquent (`OlfactoryTaxonomy::tastes()`, `byRefs()`), que resuelven lo mismo sin depender de SQL crudo. La vista quedó como código muerto por ahora; no se eliminó por si se retoma.
  - **SQL de la vista `cata_attributes`** (deriva los atributos del formulario por dimensión desde `olfactory_taxonomies`; acidity sale de la rama Sour/Acid, no de un set aparte):
```sql
CREATE OR REPLACE VIEW cata_attributes AS
-- OLFATIVAS: hojas (nivel 2) en ambas dimensiones (fragrance_aroma y flavor_aftertaste)
SELECT dim.dimension AS dimension, leaf.id, leaf.ulid, leaf.level,
       root.name_es AS categoria, sub.name_es AS subcategoria,
       leaf.name_es AS atributo, leaf.name_en AS atributo_en, leaf.color
FROM olfactory_taxonomies AS leaf
JOIN olfactory_taxonomies AS sub  ON leaf.parent_id = sub.id
JOIN olfactory_taxonomies AS root ON sub.parent_id  = root.id
CROSS JOIN (SELECT 'fragrance_aroma' AS dimension
            UNION ALL SELECT 'flavor_aftertaste') AS dim
WHERE leaf.level = 2
UNION ALL
-- ACIDITY: solo la rama 'Sour/Acid', hojas (nivel 2)
SELECT 'acidity', leaf.id, leaf.ulid, leaf.level,
       root.name_es, sub.name_es, leaf.name_es, leaf.name_en, leaf.color
FROM olfactory_taxonomies AS leaf
JOIN olfactory_taxonomies AS sub  ON leaf.parent_id = sub.id
JOIN olfactory_taxonomies AS root ON sub.parent_id  = root.id
WHERE leaf.level = 2 AND root.name_en = 'Sour/Acid'
UNION ALL
-- GENERAL (consumidor): nivel <= 1
SELECT 'general', node.id, node.ulid, node.level,
       parent.name_es, node.name_es, NULL, node.name_en, node.color
FROM olfactory_taxonomies AS node
LEFT JOIN olfactory_taxonomies AS parent ON node.parent_id = parent.id
WHERE node.level <= 1;
```
  - **Nota:** `acidity` es a la vez un **eje** (intensidad 0-15 en el JSON `descriptive`) y una **dimensión de cata** (qué tipo de ácido, desde la rama Sour/Acid). Son cosas distintas y compatibles: uno mide cuánta acidez, otro cuál.
  - Un modelo Eloquent puede **leer** de una vista como si fuera tabla (solo lectura).
- **Flujo ERD ↔ migraciones (a decidir):** dos caminos —(a) diseñar el ERD primero (MySQL Workbench, que ya conoces, o DrawDB) y escribir las migraciones según el diseño; (b) escribir migraciones primero, crear la base, y reverse-engineer el ERD con Workbench/DBeaver. Para entrega académica suele esperarse (a) porque demuestra el diseño; (b) ahorra dibujar a mano.

---

## 3. Carga de datos semilla (seeders)

- **Seeders de Laravel** para poblar desde los CSV ya generados:
  - `taxonomia-olfativa-semilla.csv` → `olfactory_taxonomy` (113 nodos: 14 raíces, 30 subcategorías, 69 hojas).
  - `referencias-calibracion-semilla.csv` → `calibration_reference` (167 referencias).
- **Recálculo de colores de la taxonomía:** al poblar/actualizar, disparar el cálculo de `color` de todos los nodos (raíz = `color_base`; hijos = derivación HSL). Recalcula todo el árbol de una vez (operación infrecuente). Idealmente vía observer/evento del modelo para que ningún cambio se escape.

---

## 4. Lógica de backend a implementar (derivados)

> En diseño se decidió que estos se **computan en backend y se almacenan**. El *qué* está decidido; el *cómo* va aquí.

- **Concordancia (Kendall's W): NO implementada todavía.** `offerings.concordance`/`concordance_level` existen como columnas pero nada las calcula — pendiente real, no solo de documentación. `casos de uso.md` (CU-04) describe el flujo completo con concordancia; el código hoy solo cubre la mitad (consenso).
- **Consenso (`offerings.consensus`): implementado** en `EvaluationController::updateConsensus()`, disparado al final de `store()` si la offering supera 5 evaluaciones `closed`+`specialist`. Detalle del cálculo (promedios, `cupping_avg` con deducción `-4d`, `cata_freq`/`main_tastes` por frecuencia con `parent_id`) documentado en `entidades.md` → `offerings.consensus`. **Gap:** no se recalcula al cerrar una evaluación vía `update()`, solo al crear una nueva vía `store()`.
- **Cupping score individual (0-100): implementado.** `EvaluationController::computeCuppingScore()`: `S = 0.65625 × Σh_i + 52.75`, redondeado a 0.25 (`Σh_i` = 7 ejes afectivos + `aroma` duplicado como fragrance). Sin deducciones `-2u -4d` a nivel individual — esas se aplican agregadas en `consensus.cupping_avg` (`-4d`; `-2u` en backlog, sin campo de uniformidad todavía).
- **Derivación HSL de colores:** función que toma el `color_base` de la raíz y ajusta luminosidad por nivel de profundidad hacia las hojas. Calcular también el color de texto por contraste (negro/blanco según brillo) — esto último en el **frontend**, al renderizar, no en el dato.
- **Validaciones por tipo de evaluador (reglas de exclusión del consumer):** en backend al validar el JSON. Consumer (`evaluator_role=consumer`): en `descriptive` sin `roast_level`, sin array `axis`, `cata` solo dimensión `general` con `ref` de nivel ≤ 1, `main_tastes` permitido. Especialista: todo, cualquier nivel.

---

## 4b. Consultas sobre campos JSON (MySQL 8)

Cómo se consultan los campos `json` del modelo. Referencia: MySQL 8 Reference Manual, "Functions That Search JSON Values" (dev.mysql.com/doc/refman/8.0/en/json-search-functions.html) y "Multi-Valued Indexes".

- **Extracción por path:**
  - `->` extrae un valor: `extrinsics->'$.origin'`.
  - `->>` extrae y desentrecomilla (valor limpio): `extrinsics->>'$.origin'`.
  - Ejemplo: `SELECT * FROM coffee WHERE extrinsics->>'$.origin' = 'Etiopía';`
- **Búsqueda en arrays JSON:**
  - `JSON_CONTAINS(doc, val, path)` — AND sobre claves de búsqueda. `SELECT * FROM product WHERE JSON_CONTAINS(attributes->'$.tags', '"organic"');`
  - `JSON_OVERLAPS(doc1, doc2)` — OR (al menos un elemento en común).
  - `val MEMBER OF(json_array)` — pertenencia a un array.
- **Rendimiento:** sin índice, filtrar JSON hace full table scan. Para los campos que se filtran de verdad (catálogo), usar **columnas generadas indexadas** (ver sección 2). Para arrays, **multi-valued indexes** optimizan `MEMBER OF`/`JSON_CONTAINS`/`JSON_OVERLAPS`. Evitar `LIKE '%...%'` sobre el JSON (nunca usa índice).
- **En Eloquent (agnóstico de motor):** la sintaxis de flecha funciona en MySQL y Postgres por igual:
  - `Coffee::where('extrinsics->origin', 'Etiopía')->get();`
  - `Product::whereJsonContains('attributes->tags', 'organic')->get();`
  - Eloquent traduce al SQL del motor; por eso el cambio de motor apenas afecta el código de consultas.
- **Criterio de uso (buenas prácticas):** los bloques de evaluación (`descriptive`/`affective`, con sus ejes y catas) se leen **como bloque** y se parsean en backend para el cálculo, no se filtran masivamente → JSON es ideal. Solo el **catálogo** (origen, variedad, proceso, tags de producto) se filtra → esos campos a columnas generadas indexadas. No usar JSON para datos de esquema fijo que se consultan mucho (van en columnas normales).

---

## 5. Frontend / UI

- **Rueda de sabores (flavor wheel):** sunburst zoomable con **D3.js** (no PHP). Laravel sirve la taxonomía como JSON jerárquico vía endpoint; el frontend renderiza y consume el `color` ya resuelto. La misma rueda puede ser interfaz de captura CATA (clic en segmento = seleccionar descriptor). Patrón de referencia: Bostock/Davies, pero construido desde cero con D3 v7 y paleta propia (no la SCA, por copyright).
- **Mapa de locales:** **Leaflet.js** (estándar Laravel; tiles OpenStreetMap gratis). Consume `locations.latitud` / `longitud` (decimal). Paquete de referencia: `larswiegers/laravel-maps` (componentes Blade). Búsqueda por proximidad vía **Haversine** en SQL (sin PostGIS). PostGIS a backlog si la búsqueda geográfica crece.
- **Geocoding (opcional):** **Geocoder PHP** (con Nominatim/Google) para convertir direcciones a coordenadas cuando la cafetería ingresa su dirección.

---

## 6. Integraciones de captura (fase posterior)

- Formularios de evaluación (descriptive + affective) — cuando se diseñe el flujo de captura.
- La captura escribe en las entidades separadas vía el contenedor `evaluation`.

---

## 7. Decisiones técnicas pendientes de implementación

- **Concordancia (Kendall's W): no implementada.** Es el pendiente más grande respecto al diseño original — `offerings.concordance`/`concordance_level` sin lógica que los calcule.
- **Uniformidad (`u`) en la fórmula de `cupping_avg`:** deferida a backlog explícitamente. Falta decidir de dónde sale el dato (no hay campo de uniformidad en el modelo hoy).
- **Recalcular consenso al cerrar vía `update()`:** hoy solo se dispara desde `store()`.
- **Valores hex de la paleta de colores propia** para las ~14 categorías raíz (convenciones semánticas, no SCA).
- **Qué campos del catálogo extraer a columnas generadas indexadas** (`products.attributes`, `coffees.extrinsics`) según los filtros reales que se implementen.
- **Flujo ERD ↔ migraciones:** decidir cuál de los dos caminos (ver sección 2).
- **Patrón vs. valores en concordancia:** Kendall's W (perfil) fijado para el MVP; migración a ICC (valores absolutos) si se requiere más adelante — sigue sin implementarse ninguna de las dos.
- **`CataAttribute` / vista `cata_attributes`:** evaluar si se elimina (código muerto, no usado por el formulario) o se retoma.