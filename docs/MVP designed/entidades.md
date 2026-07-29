# Modelo de entidades — esquema MySQL

> Definición de campos por entidad (MySQL 8). 
>
> **Convención:** todas las tablas tienen `id` BIGINT UNSIGNED PK AI + `ulid` CHAR(26) UQ NN (identificador público) + `created_at`/`updated_at`. Se omiten abajo para no repetir. Propiedades: PK · FK · UQ (unique) · NN (not null) · NULL (nullable) · DEFAULT · der (derivado en backend).

---

## Actores (User Layer)

### `roles`
| Columna | Tipo MySQL | Propiedades |
|---|---|---|
| `name` | ENUM('specialist','consumer','coffeeshop') | NN, DEFAULT 'consumer' |
| `description` | VARCHAR(255) | NULL |

### `users`
| Columna | Tipo MySQL | Propiedades |
|---|---|---|
| `name` | VARCHAR(60) | NN |
| `surname` | VARCHAR(60) | NN |
| `email` | VARCHAR(150) | UQ, NN |
| `role_id` | BIGINT UNSIGNED | FK→roles, NN |
| `email_verified` | TINYINT | NN, DEFAULT 0 |

### `specialist_profiles` (extensión 1-1 de users — BACKLOG)
| Columna | Tipo MySQL | Propiedades |
|---|---|---|
| `user_id` | BIGINT UNSIGNED | FK→users, NN |
| `verified` | TINYINT | NN, DEFAULT 0 |

### `locations` (punto de venta; pertenece a un user-negocio)
| Columna | Tipo MySQL | Propiedades |
|---|---|---|
| `user_id` | BIGINT UNSIGNED | FK→users, NN |
| `name` | VARCHAR(150) | NN |
| `contact` | JSON | NULL |
| `latitud` | DECIMAL(10,8) | NN |
| `longitud` | DECIMAL(11,8) | NN |
| `description` | TEXT | NULL |

UNIQUE (`user_id`,`name`) — un negocio no repite nombre de local.

**`contact` (JSON):**
```json
{ "phone": "+34 600 000 000", "email": "hola@local.com", "web": "https://local.com", "instagram": "@local", "address": "C/ Mayor 5, Barcelona" }
```

---

## Catálogo (Product Layer)

### `roasteries` (tostador)
| Columna | Tipo MySQL | Propiedades |
|---|---|---|
| `name` | VARCHAR(150) | NN |
| `contact` | JSON | NULL |
| `description` | TEXT | NULL |

`contact`: misma forma que `locations.contact` (incluye `address`).

### `coffees` (el grano; objeto evaluable)
| Columna | Tipo MySQL | Propiedades |
|---|---|---|
| `name` | VARCHAR(150) | NN |
| `roastery_id` | BIGINT UNSIGNED | FK→roasteries, NN |
| `roast_level` | ENUM('light','medium_light','medium','medium_dark','dark') | NN, DEFAULT 'medium' |
| `extrinsics` | JSON | NN |

`medium` = línea base de cupping de specialty (referencia de desviación, no se fuerza).

**`extrinsics` (JSON):**
```json
{ "origin": {"region": "Huila", "country": "Colombia"}, "producer": "Finca El Mirador", "variety": "Caturra", "process": "lavado", "certification": ["orgánico"], "traceability": "lote único", "lot": "2026-04-A" }
```

### `products` (catálogo maestro del negocio; precio a nivel de producto)
| Columna | Tipo MySQL | Propiedades |
|---|---|---|
| `business_id` | BIGINT UNSIGNED | FK→users, NN |
| `category` | ENUM('coffee_drink','non_coffee_drink','food','coffee_beans','barista_material') | NN |
| `name` | VARCHAR(150) | NN |
| `price` | DECIMAL(10,2) | NN |
| `coffee_id` | BIGINT UNSIGNED | FK→coffees, NULL |
| `attributes` | JSON | NULL |
| `active` | TINYINT | NN, DEFAULT 1 |

UNIQUE (`business_id`,`name`). `coffee_id` NULL salvo coffee_drink / coffee_beans (puente al café evaluable).

**`attributes` (JSON):** varía por `category`.
```json
{ "size": "mediano", "temperature": "caliente", "milk": "avena", "allergens": ["lactosa"], "tags": ["para_llevar"] }
```

### `catalog` (asociativa locations ↔ products: qué ofrece cada local)
| Columna | Tipo MySQL | Propiedades |
|---|---|---|
| `product_id` | BIGINT UNSIGNED | FK→products, NN |
| `location_id` | BIGINT UNSIGNED | FK→locations, NN |
| `active` | TINYINT | NN, DEFAULT 1 |

UNIQUE (`location_id`,`product_id`) sugerido — un producto no se duplica en el mismo local.

---
## Evaluación

### `evaluations` (contenedor; descriptive y affective como JSON)
| Columna | Tipo MySQL | Propiedades |
|---|---|---|
| `offering_id` | BIGINT UNSIGNED | FK→offerings, NN |
| `evaluator_id` | BIGINT UNSIGNED | FK→users, NN |
| `evaluator_role` | ENUM('specialist','consumer','coffeeshop') | NN |
| `extraction_method` | VARCHAR(60) | NULL |
| `status` | ENUM('open','closed') | NN, DEFAULT 'open' (solo closed entra a cálculos) |
| `descriptive` | JSON | NN |
| `affective` | JSON | NULL |
| `note` | TEXT | NULL |

Validación por rol en backend (reglas de exclusión del consumer). Concordancia y consenso se calculan en backend parseando los JSON de todas las evaluaciones cerradas del offering.

**`descriptive` (JSON)** — cabecera + ejes (solo `aroma` activo, ver nota) + catas + main_tastes. Estructura real implementada (`App\Livewire\Evaluations\EvaluationForm`), **distinta del boceto original**: `axis`/`note` son **mapas por clave** (no arrays de objetos), para que `wire:model` los bindee directo; la transformación a este formato final ocurre solo al guardar (`EvaluationController::store/update`).
- `axis`: escala 0-15 (intensidad). En la práctica solo `aroma` se captura como descriptivo puro — flavor/aftertaste/acidity/sweetness/mouthfeel/overall existen como claves en el mapa pero su intensidad 0-15 no se usa en la UI actual (la UI combina sabor+retrogusto en una sola card con dos sliders, ver `evaluation-axis.blade.php`).
- `cata`: **anidado por dimensión** (`aroma`, `flavor_aftertaste`), cada una una lista de refs — no hay campo `dimension` dentro de cada item, la clave del objeto ya lo indica. `ref` = id del nodo de `olfactory_taxonomies` (sin FK, es JSON). Desde hoy cada entrada también guarda **`parent_id`** (el `parent_id` real del nodo en `olfactory_taxonomies`), agregado por `descriptor-cascade.blade.php` al togglear — necesario para reconstruir la jerarquía sin re-consultar la taxonomía en cada lectura. Evaluaciones guardadas **antes** de este cambio no tienen `parent_id` en sus refs.
- `main_tastes`: hasta 2, guardados como refs `{ref, level}` (no strings) — igual formato que `cata`, pero sin `parent_id` (la categoría `main_tastes` es de un solo nivel, no tiene jerarquía).
```json
// DESCRIPTIVE — formato real (mapa por clave, no array)
{
  "roast_level": "medium",
  "main_tastes": [
    { "ref": 24, "level": 0 },
    { "ref": 109, "level": 0 }
  ],
  "axis": {
    "aroma": 8, "flavor": 10, "aftertaste": 7,
    "acidity": 9, "sweetness": 6, "mouthfeel": 7, "overall": 8
  },
  "cata": {
    "aroma":             [{ "ref": 20, "level": 2, "parent_id": 19 }],
    "flavor_aftertaste": [{ "ref": 29, "level": 2, "parent_id": 25 }]
  },
  "note": {
    "aroma": "floral", "flavor_aftertaste": null, "acidity": "cítrica",
    "sweetness": null, "mouthfeel": "sedoso", "overall": null
  }
}
```
Consumer: **no implementado todavía** (solo existe la versión specialist; el rol `consumer` está en el ENUM pero no tiene formulario ni validación propia construida).

**`affective` (JSON)** — calidad (impression of quality, escala 1-9) + ejes + defectos. Mismo patrón de mapas-por-clave que `descriptive`.
- `axis`: mismos 7 ejes (incl. `aftertaste` propio), escala 1-9, todos activos en la UI (`affective-bean.blade.php`, iconos de grano 1-9).
- `cupping_score`: 0-100, der. Coeficiente **ya implementado** (`EvaluationController::computeCuppingScore`): `S = 0.65625 × Σh_i + 52.75`, redondeado a 0.25; `Σh_i` = suma de los 7 ejes afectivos + `aroma` duplicado (hace de "fragrance", que no existe como eje propio). **No** incluye las deducciones `-2u -4d` a nivel de evaluación individual — esas se aplican agregadas en `offerings.consensus.cupping_avg` (ver abajo).
- `cata`: anidado por dimensión (`mouthfeel`, `defects`), mismo formato `{ref, level, parent_id}` que `descriptive.cata`.
```json
// AFFECTIVE — formato real (mapa por clave, no array)
{
  "is_defective": true,
  "cupping_score": 84,
  "axis": {
    "aroma": 7, "flavor": 8, "aftertaste": 7,
    "acidity": 7, "sweetness": 7, "mouthfeel": 7, "overall": 8
  },
  "cata": {
    "defects":   [{ "ref": 52,  "level": 2, "parent_id": 47 }],
    "mouthfeel": [{ "ref": 116, "level": 1, "parent_id": 114 }]
  },
  "note": {
    "aroma": null, "flavor_aftertaste": null, "acidity": null,
    "sweetness": null, "mouthfeel": null, "overall": null
  }
}
```
`note.overall` en `affective` está unificado con la columna `evaluations.note` (el textarea de notas del eje "overall" escribe directo en `note`, no en `affective.note.overall`).

### `offerings` (clase asociativa locations ↔ coffees + derivados)
| Columna | Tipo MySQL | Propiedades |
|---|---|---|
| `location_id` | BIGINT UNSIGNED | FK→locations, NN |
| `coffee_id` | BIGINT UNSIGNED | FK→coffees, NN |
| `evaluation_count` | INT UNSIGNED | NN, DEFAULT 0, der |
| `defective_evaluation_count` | INT UNSIGNED | NN, DEFAULT 0, der |
| `consensus` | JSON | NULL, der |
| `concordance` | DECIMAL(4,3) | NULL, der (Kendall's W 0-1; no se muestra crudo) |
| `concordance_level` | ENUM('low','acceptable','good','excellent') | NULL, der (rangos Cicchetti) |
| `verification_status` | ENUM('provisional','verified') | NN, DEFAULT 'provisional', der |

UNIQUE (`location_id`,`coffee_id`) — un offering por dupla.

**`consensus` (JSON)** — derivado, calculado en `EvaluationController::updateConsensus()` cada vez que se crea una evaluación (`store()`), si la offering ya tiene **más de 5** evaluaciones `status=closed` + `evaluator_role=specialist` (`$n`). No se recalcula al cerrar una evaluación vía `update()` (gap conocido, ver nota abajo).
- `axis_avg`: promedio de `affective.axis` (las 7 claves, incl. `aftertaste`) entre las evaluaciones que califican.
- `cupping_avg`: `promedio(affective.cupping_score) − (4 × d × 5 ÷ n)`, redondeado a 0.25. `d` = cantidad de evaluaciones con `affective.is_defective = true` entre las que califican. Es la aplicación agregada de la deducción `-4d` del protocolo SCA (normalizada a un panel de 5 tazas, ya que `n` evaluaciones ≈ `n` tazas). **`-2u` (uniformidad) queda en backlog** — no hay campo de uniformidad en el modelo todavía, así que `u = 0` por ahora.
- `main_tastes`: frecuencia de refs de `descriptive.main_tastes` entre las evaluaciones que califican. Sin `parent_id` (categoría de un solo nivel).
- `cata_freq`: frecuencia de refs juntando **las 4 listas** de cata de cada evaluación (`descriptive.cata.aroma`, `descriptive.cata.flavor_aftertaste`, `affective.cata.mouthfeel`, `affective.cata.defects`), con `parent_id` resuelto desde `olfactory_taxonomies` (necesario para agrupar por jerarquía en la vista, ver `cata-wheel-static.blade.php`).
```json
{
  "axis_avg": { "aroma": 7.4, "flavor": 8.1, "aftertaste": 7.6, "acidity": 8.0, "sweetness": 6.5, "mouthfeel": 7.0, "overall": 7.8 },
  "cupping_avg": 83.5,
  "main_tastes": [
    { "ref": 24, "level": 0, "count": 4 }
  ],
  "cata_freq": [
    { "ref": 1,  "level": 0, "parent_id": null, "count": 5 },
    { "ref": 19, "level": 1, "parent_id": 1,    "count": 5 },
    { "ref": 20, "level": 2, "parent_id": 19,   "count": 5 }
  ]
}
```
**Gap conocido:** el recálculo solo se dispara desde `store()` (creación). Una evaluación creada `open` y cerrada después vía `update()` no dispara el recálculo en ese momento — decisión explícita para no ampliar el alcance, pendiente si se necesita cubrirlo.
---

## Taxonomía (Presentation Layer)

### `olfactory_taxonomies` (árbol auto-referencial 3 niveles; alimenta rueda y CATA)
| Columna | Tipo MySQL | Propiedades |
|---|---|---|
| `parent_id` | BIGINT UNSIGNED | FK→olfactory_taxonomies, NULL |
| `level` | TINYINT | NN (0 raíz / 1 subcategoría / 2 hoja) |
| `name_en` | VARCHAR(60) | NN |
| `name_es` | VARCHAR(60) | NN |
| `description_en` | VARCHAR(250) | NULL |
| `description_es` | VARCHAR(250) | NULL |
| `color_base` | CHAR(7) | NULL (hex, solo raíces) |
| `color` | CHAR(7) | NULL, der (hex; raíz=color_base, hijos=HSL) |

Semilla: `taxonomia-olfativa-semilla.csv` (113 nodos). La vista `cata_attributes` (abajo) deriva de aquí los atributos del formulario por dimensión.


### Vista `cata_attributes` (existe en BD, no usada por el formulario actual)

La vista **sí está creada** (migración `create_cata_attributes_view.php`, modelo `App\Models\CataAttribute`), pero el formulario de evaluación (`EvaluationForm`) **no la consulta** — en su lugar usa scopes de Eloquent directos sobre `olfactory_taxonomies` (`OlfactoryTaxonomy::tastes($levels, $category)`, `byRefs()`), que dan el mismo resultado sin depender de una vista SQL. `CataAttribute` queda importado pero sin uso en el código actual (candidato a limpieza si se confirma que no se retoma).

Deriva de `olfactory_taxonomies` los atributos marcables por dimensión. Solo lectura (UNION). Filtrar por `dimension` (`fragrance_aroma`, `flavor_aftertaste`, `acidity`, `general`). Olfativas = hojas (nivel 2) en ambas dimensiones; acidity = rama Sour/Acid (nivel 2); general = nivel ≤ 1.

Columnas reales (según la migración): `dimension`, `id`, `level`, `category`, `sub_category`, `attribute`, `attribute_en`, `color` (sin `ulid`, sin `categoria`/`subcategoria`/`atributo` en español — los nombres de columna quedaron en inglés).
