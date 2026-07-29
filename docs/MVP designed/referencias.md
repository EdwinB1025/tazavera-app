# Referencias del estándar CVA / SCA — material de consulta

> Notas de referencia extraídas del documento de proyecto (`proyecto-mvc-laravel-ideas.md`). Son material del estándar SCA Coffee Value Assessment (CVA v2, provisional jun-2024) y del WCR Sensory Lexicon, conservado con detalle para consulta. Cada nota indica su **aplicabilidad al MVP** ([Aplica] / [No aplica] / [Referencia]). Las *decisiones de diseño* derivadas de estas referencias viven en el documento de proyecto, no aquí.

---

## Procedimiento de cupping (pasos)

- **Paso 2 — brewing y evaluación del aroma. [Aroma: sí aplica; aroma de costra: no aplica]** En el cupping formal, tras la fragancia se infusiona vertiendo agua hasta el borde; se forma una costra ("crust") en la superficie. Se evalúa el aroma de la costra intacta (3-5 min) y luego al romperla removiendo tres veces, oliendo los vapores; ambos momentos forman la sección aroma (puramente olfativa). Después se retiran posos/aceites (skimming) y se enjuagan las cucharas entre tazas para no contaminar. **Con batch brewing (caso del MVP), el propio estándar indica: no hay evaluación de aroma de costra ni rotura de costra; el aroma se evalúa del café recién preparado.** Aplicabilidad: el *aroma del café preparado* **sí aplica** (respaldado por el protocolo de batch brewing); el resto —costra, rotura, skimming, higiene de cucharas— es mecánica del cupping y **no aplica**. **Nota de debilidad:** no se hará análisis de aroma sobre la costra (crust); es una pérdida de resolución olfativa frente al cupping formal, asumida como desviación del método.

- **Paso 3 — liquoring mientras el café se enfría. [Aplica — núcleo de la evaluación del MVP]** Tras el skimming, el café se deja enfriar a ~70°C y empieza el "liquoring": sorber una cucharada cubriendo lengua y paladar, evaluar en boca y expulsar (para no ingerir demasiada cafeína), en al menos tres rondas conforme el café se enfría. Secciones evaluadas:
  1. *Flavor* — percepción compuesta gustativa + retronasal con el café en boca.
  2. *Aftertaste* — percepción gustativa + retronasal de los residuos tras expulsar/tragar; con duración en el tiempo.
  3. *Acidity* — percepción gustativa estructurada en torno a la acidez (sourness).
  4. *Sweetness* — percepción gustativa o retronasal de dulzor.
  5. *Mouthfeel* — sensación táctil: viscosidad (cuerpo), textura, astringencia (sensación de boca seca).
  6. *Overall* — percepción holística que combina todas las secciones previas.
  Con batch brewing (descriptive), se sorbe de una o varias tazas, manteniendo la técnica de sorber con cuchara (salvo que se trague) para facilitar la percepción retronasal.
  **Aplicabilidad:** estos seis ítems se evalúan sobre el café ya preparado y enfriándose — exactamente lo que el especialista tiene con el café de cafetería; no dependen de costra ni molido seco. Son el **núcleo de los ejes que sí captura el MVP**.
  **Assumption (validada):** sorber con cuchara es alcanzable en una cafetería; la percepción retronasal se mantiene.

## Preparación y condiciones (parámetros de "hardware")

- **Especificaciones del agua (Tabla 5 del CVA). [No aplica al MVP / debilidad metodológica]** Rangos aceptables: cloro = ninguno; dureza cálcica = 50-175 ppm CaCO₃; alcalinidad ≈ 40-70 ppm CaCO₃; pH = 6-8. Las características del agua impactan mucho los atributos sensoriales. Es parámetro de preparación, no dato capturado. En el MVP la cafetería usa su propia agua, fuera de control; no puede garantizarse el cumplimiento. **Debilidad metodológica:** dos evaluaciones del mismo café en cafeterías con aguas distintas podrían diferir por el agua, no por el café (ruido análogo al "roasting problem").

- **Ratio café-agua del cupping. [No aplica al MVP / referencia para cupping formal futuro]** Se mide el volumen del recipiente (pesando el agua que cabe lleno al borde, densidad ≈ 1 g/mL → 250 g ≈ 250 mL); la masa de café por taza se calcula a **8.25 g por 150 mL de capacidad**. Es parámetro de preparación, no dato de evaluación. En el MVP el especialista evalúa café ya preparado por la cafetería, no monta un cupping con ratio controlado.

- **Molienda del cupping vs. filtrado. [No aplica al MVP / referencia de contexto]** El café para cupping se muele de modo que 70-75% pase por un tamiz de malla 20 US (850 μm), *ligeramente más grueso* que la molienda típica de filtro de papel. La cita marca una **diferencia**, no una equivalencia — no justifica por sí sola que el café de cafetería sea intercambiable con el del cupping. Lo que aporta: cupping y filtro están *cercanas* (la SCA las contrasta en el mismo marco), situando el filtrado como vecino próximo. La justificación de usar café de cafetería no es la equivalencia, sino la aproximación deliberada con método/contexto registrado.

- **Batch brewing autorizado para el descriptive. [Sí aplica al MVP — referencia CLAVE]** El descriptive **no evalúa uniformidad**, por lo que **no requiere el método de cupping**: admite batch brewing (filtro o prensa francesa), ratio recomendado 55-60 g/L. **Implicación fuerte:** para el descriptive, evaluar café preparado al estilo cafetería **no es desviación del estándar, es una opción que el CVA contempla**. Cadena lógica: uniformidad → múltiples tazas → cupping; el descriptive no mide uniformidad → no requiere cupping. **Asimetría:** el descriptive con café de cafetería está respaldado por el estándar; el affective con café de cafetería sigue siendo desviación justificada por "aproximación con método registrado".

## Uniformidad del lote y número de tazas

- **Requisito de cinco tazas en el affective. [No aplica al MVP en el lado affective]** El affective canónico exige **cinco tazas por café** para evaluar la uniformidad del lote de forma consistente (el formato combinado también, y recomienda reducir cafés por sesión por su mayor detalle). El affective del MVP es sobre una sola preparación, así que no se cumple. Confirma con un número explícito la asimetría: el affective sobre una taza es la **desviación más marcada** respecto al cupping. Donde el affective canónico saca robustez de cinco tazas, el MVP la saca de la concordancia inter-especialista (N evaluaciones independientes ≈ N tazas).

- **Uniformidad del lote y `Non-Uniform Cups`. [No aplica al MVP / sí aplica a cupping formal futuro]** En el cupping, cada taza recibe un set distinto de granos; flavor uniforme entre tazas = alta uniformidad del lote; una o más tazas distintas = no-uniformidad. Origen del campo `Non-Uniform Cups`: la uniformidad es propiedad **del lote** (emergente de comparar tazas), no un atributo sensorial individual. Sobre una sola preparación **no es medible** — el campo se conserva inactivo, solo aplicaría bajo cupping formal de lote. Es uno de los datos que se pierden al aproximar.
  - **Mecánica (CVA):** una taza se marca no-uniforme si presenta una diferencia **cualitativa** respecto al resto (una característica distintiva presente o ausente en una o más tazas), **sin importar si es más o menos deseable** — basta con que sea cualitativamente distinta. Las casillas corresponden a **tazas físicas**: si la diferencia está en las tazas #1 y #5, se marcan la primera y la quinta casillas. Implica múltiples tazas físicas simultáneas → físicamente imposible sobre una sola preparación (confirma por qué no aplica al MVP).
  - **Matiz: uniformidad ≠ calidad.** La uniformidad mide *consistencia*, no *calidad*: una taza incluso mejor que las demás cuenta como no-uniforme si rompe la consistencia del lote.
  - **Dos "consistencias" distintas (no confundir):**
    - *Uniformidad del lote (CVA, no aplica):* consistencia **entre tazas físicas** del mismo lote, en una sesión, por un catador. ¿Es homogéneo el lote?
    - *Concordancia inter-especialista (proyecto, aplica, derivada):* consistencia **entre evaluadores** sobre el mismo café. ¿Convergen los especialistas? — Mide convergencia de *juicios entre sujetos*, no diferencia cualitativa entre *objetos físicos*. **No es un proxy de magnitud** de la uniformidad del lote (un lote uniforme puede tener evaluadores que divergen y viceversa); es un **sustituto metodológico** de su *función* (dar robustez/validez al dato). Nomenclatura adoptada: `concordancia_inter_especialista`, tag **"sustituto de las 5 tazas del CVA"** (las 5 evaluaciones independientes cumplen el papel que cumplían las 5 tazas físicas).
  - *Nota de diseño (cupping formal futuro):* la mecánica "casilla = taza física #N" implica que `Non-Uniform Cups` no es un conteo sino un *set de tazas marcadas* (cuáles de las N); el modo formal necesitaría granularidad por taza, no un entero.

## Roast Level (medición)

- **Medición del Roast Level. [Referencia para funcionalidades futuras]** Cuatro métodos alternativos: colorimetría (CIELAB o Agtron/SCA Roast Color Classification), espectrometría infrarroja (dispositivo Agtron), pérdida de materia seca, o aumento de volumen del grano. El nivel recomendado para cupping de specialty se describe como "medium"; cualquier desviación debe reportarse a todas las partes por transparencia. Implicación futura: registrar el método de medición y un indicador de desviación respecto al "medium", alineado con el principio de señal de confianza.
  - *Precisión:* los roast meters especializados (muchos por espectrometría IR) suelen ser más precisos que otros métodos.
  - *Lecturas objetivo por instrumento (Tabla 4 del CVA):* Agtron "Gourmet" = 63.0; Agtron "Commercial" = 48.0; Colorette 3b by Probat = 96.0; Colortrack = 62.0. La escala depende del instrumento, así que el valor solo es interpretable junto con el instrumento usado.

## Referencias de calibración del evaluador

- **Referencias de gusto de los Main Tastes (6.3 del CVA). [Referencia de calibración / no es dato capturado]** Referencias cualitativas de las cinco modalidades gustativas: Salado = solución de NaCl 0,15% (sal de mesa refinada); Ácido = ácido cítrico 0,015%; Dulce = sacarosa 1,0% (azúcar blanca); Amargo = café tostado oscuro (~Agtron #35); Umami = glutamato monosódico (MSG) 1,0%. Para intensidades, el CVA remite al WCR Lexicon. Confirma los cinco Main Tastes (gusto puro, distinto de los descriptores olfativos). Material de calibración del evaluador, no campo capturado. **Ventaja ES/CO:** son soluciones químicas simples accesibles globalmente, sin el problema de disponibilidad de las referencias de supermercado estadounidense del WCR.

- **Carácter de la acidez (6.4 del CVA). [Aplica con advertencia de diseño]** La acidez tiene dos dimensiones que no se capturan igual: la *intensidad* (sourness) es medible y referenciable (escala 0-15), pero el *carácter/calidad* ("jugosa", "brillante") **no tiene referencias sensoriales estandarizadas**, y usar ácidos orgánicos como referencia induce a error porque los cafés mezclan muchos ácidos. **Decisión del estándar:** la beta del CVA (abril 2023) introdujo CATA de "Dry/Sweet acidity", pero el provisional (junio 2024) los **eliminó** por falta de referencias científicas; manda describir el carácter **solo con texto libre**. (La decisión de diseño del eje acidez del proyecto —opción C, descriptores perceptibles como proxy— vive en el documento de proyecto.)

- **Tipos de notas afectivas (CVA). [Material para la guía de uso — NO afecta la lógica de negocio]** El campo de notas del affective es **texto libre sin lógica de negocio** (no se tipifica, no se parsea, no entra en agregaciones ni concordancia). Los cuatro tipos siguientes son orientación para la **guía de uso** —cómo redactar una buena nota—, no estructura de datos:
  1. *Just About Right (JAR)* — si hay demasiado, muy poco o "lo justo" de un carácter. Ej: "muy ácido", "muy fermentado", "le falta dulzor", "le falta cuerpo", "bien balanceado".
  2. *Ajuste a propósito o carácter* — qué tan bien encaja con un fin o perfil. Ej: "ideal para el café del mes", "Yirgacheffe prototípico", "no percibo el Gesha".
  3. *Qué representan los atributos, con juicio de calidad* — ej: "el sabor floral lo hace muy elegante", "el retrogusto a ceniza es decepcionante", "el cuerpo sedoso es su mejor cualidad". Aunque incluyan descriptores, se usan para sustentar una impresión de calidad: deben indicar la *deseabilidad* del atributo, no solo nombrarlo.
  4. *Representación general, intuitiva o simbólica* — ej: "elegante", "salvaje", "exótico", "potente". Precaución: suelen no traducir entre lenguas/culturas; si se usan, acompañar con contexto u otras notas. Ej: "funky" es vago, intraducible, y deseable para unos y a evitar para otros.

- **Defectos: tres marcos distintos y postura del CVA. [Material para la guía de uso + fundamenta el modelo de defectos]** Conviene no confundir tres cosas que el mundo SCA trata por separado:
  1. *Sistema clásico SCAA 2004/2005 (taint/fault)* — el cupping form viejo. Defecto = flavor negativo en dos niveles: **taint** (notable, no abrumador, −2 pts) y **fault** (vuelve la muestra impalatable, −4 pts). Procedimiento: clasificar (taint/fault) → describir ("sour", "rubbery", "ferment", "phenolic"…) → anotar. Los ejemplos eran ilustrativos: el catador *describe* el defecto, no lo elige de un catálogo cerrado.
  2. *Green coffee defects (físicos)* — clasificación SCA de defectos del grano verde (primarios/secundarios), evaluados visual y manualmente sobre 350 g. Esta sí es lista cerrada, pero **física/visual** → corresponde al Physical Assessment, fuera del MVP.
  3. *CVA (estándar del proyecto)* — **abandonó deliberadamente la categorización rígida**. Atributos como sobremaduro o isovalérico se han vuelto controvertidos (procesos natural/honey, fermentaciones intensas); "deseable/indeseable" evoluciona con el mercado. Regla del CVA: identificar el defecto **lo más específicamente posible** ("si no puedes identificarlo como defecto, probablemente no lo es"), evitando paraguas amplios (no meter todo bajo "phenolic"). Antes de penalizar, el catador debe tener claro que es un defecto y nombrarlo específicamente.
  - **WCR vs. CVA (capas, no rivales):** el **WCR Lexicon es neutral en valor** — no juzga, solo describe presencia e intensidad; "phenolic"/"musty" son descriptores neutros, incluidos porque aparecieron en las muestras, no porque sean malos. El **CVA aporta el juicio**: decide si ese atributo es defecto, contextual y temporalmente. Por tanto: el vocabulario para *nombrar* un defecto sale del WCR (taxonomía de 110 atributos); el *veredicto* de que es defecto lo pone el CVA/catador. El defecto vive en el **affective** (juicio), no en la taxonomía descriptive (neutral).
  - **Consecuencia para el modelo:** **no hay lista cerrada de defectos** (ni el CVA ni el sistema viejo la imponen; el CVA la desaconseja). La "lista completa" de defectos posibles es, de hecho, la taxonomía WCR entera: cualquier atributo puede ser juzgado defecto según contexto (isovalérico = descriptor neutro que puede ser defecto o virtud en un natural fermentado).

### Entidad `calibration_references` (estructura)

Tabla de referencias físicas de calibración, asociada a la taxonomía olfativa. Es material de consulta/calibración del evaluador (no dato capturado en la evaluación), por eso se documenta aquí y no en el modelo de entidades operativo. Semilla: `referencias-calibracion-semilla.csv` (167 referencias). Relación: 1 nodo de `olfactory_taxonomies` → muchas referencias.

| Columna | Tipo MySQL | Propiedades |
|---|---|---|
| `id` | BIGINT UNSIGNED | PK, AI |
| `ulid` | CHAR(26) | UQ, NN |
| `descriptor_id` | BIGINT UNSIGNED | FK→olfactory_taxonomies, NN |
| `descriptor_en` | VARCHAR(100) | NN |
| `reference` | VARCHAR(255) | NN |
| `how_to_prepare` | TEXT | NULL |
| `suggested_brands` | TEXT | NULL |

Si se implementa como tabla en la base (para servir referencias al evaluador en la UI de calibración), sigue este esquema. Para el MVP puede quedar solo como CSV de consulta.

---

## Glosarios (referencia terminológica)

### Términos sensoriales

- **Gustativo** — sentido del gusto; estructura principal: papilas gustativas.
- **Olfativo** — sentido del olfato; nariz, cavidades nasales y bulbo olfativo.
- **Ortonasal** — vía de entrada del olor por la nariz, al inhalar (olor del entorno).
- **Retronasal** — vía de entrada desde la parte posterior de la boca, al exhalar (componente olfativo del flavor).
- **Táctil** — sentido del tacto; aquí, el "mouthfeel".

Mapa de los siete ejes: Fragancia = ortonasal (café seco); Aroma = ortonasal (café húmedo); Flavor = gustativo + retronasal; Aftertaste = flavor persistente tras tragar; Acidez y dulzor = gustativos; Mouthfeel = táctil.

### Términos de cupping

- **Intensity** (descriptive) — fuerza percibida de un estímulo; no implica calidad ni deseabilidad.
- **Impression of quality** (affective) — opinión sobre lo distintivo y deseable de una sección, anclable a preferencia de mercado conocida.
- *(Intensity vs. Impression of quality = fundamento de la separación descriptive/affective: "termómetro vs. veredicto".)*
- **Alignment / inter-subjetividad** — cuando un grupo de catadores acuerda el score affective; refleja inter-subjetividad (el affective es subjetivo pero converge entre entrenados).
- **Cupping** — catar varias tazas por muestra, cada una con un set distinto de granos.
- **Cupping step / section** — actividad (fragancia, brewing, liquoring) / categoría evaluada.
- **Brewing / Brew** — añadir agua caliente a la molienda / la bebida resultante.
- **Breaking the crust** — remover la costra que se forma en la superficie.
- **Skimming** — retirar posos, espuma y aceites tras romper la costra.
- **Liquoring** — catar el brew varias veces mientras se enfría.

### Términos de defectos

- **Physical defect** — propiedad material del grano, percibida **visualmente** (Physical Assessment, fuera del MVP).
- **Sensory defect** — propiedad aromática/gustativa negativa, evaluada **catando** (Affective Assessment; sí en el modelo: Moldy/Phenolic/Potato). *Physical = vista, sensory = gusto/olfato; no mezclar.*
- **Roasting problem** — desviación del tueste (fuera de rango, subdesarrollado, quemado, "baked"); impacto sensorial potencial, introduce ruido. Posible flag junto al `Roast Level` como señal de confianza.

### Hardware vs. software (fundamentación de la diferenciación de evaluadores)

- **Hardware (mecánica)** — operaciones del cupping (preparar, catar). Se aprende rápido.
- **Software (criterio)** — métodos y criterio para describir el flavor y determinar la impression of quality. Se desarrolla con años de exposición.
- La barrera especialista/consumidor no es la mecánica (hardware) sino el criterio calibrado (software). Fundamenta la validación por conocimiento/exposición (opción C) y las entidades affective separadas.

---

## Resumen de la metodología implementada (mapa estándar → decisión)

> Índice de qué del estándar CVA/SCA se aplicó y cómo. El detalle de cada decisión vive en `proyecto-mvc-laravel-ideas.md`; aquí solo el mapa de correspondencia.

**Base del sistema:** SCA Coffee Value Assessment (CVA) v2, Standard provisional jun-2024 (reemplaza el SCA Cupping Form 2004). De los cuatro tipos de evaluación del CVA (physical, descriptive, affective, extrinsic), el MVP implementa tres como **entidades separadas** (no promediables): `Descriptive`, `AffectiveEspecialista`, `AffectiveConsumidor`.

| Elemento del estándar | Cómo se implementó en el MVP |
|---|---|
| Descriptive (intensidad 0-15, 7 ejes) | Entidad `Descriptive`; CATA + Main Tastes + texto libre. Café de cafetería respaldado por **batch brewing** (el estándar lo autoriza para descriptive). |
| Affective (impression of quality 1-9) | Entidad `AffectiveEspecialista`; 9 puntos con anclas, derivación a 100 puntos (presentación). |
| Affective de consumidor | Entidad `AffectiveConsumidor`; escala hedónica de 4 opciones en palabras, homologada a 1-9 para comparar (no promediar) con el especialista. |
| Fragancia (molido seco) | **No aplica** (cafetería no entrega molido seco); campo inactivo, se evalúa solo aroma. |
| Uniformidad del lote (5 tazas) | **No aplica** (una sola preparación); sustituida por **concordancia inter-especialista derivada** (mín. 5 evaluaciones ≈ 5 tazas). |
| Carácter de la acidez (texto libre en el estándar) | Eje acidez = intensidad 0-15 + texto libre + CATA propio de **descriptores perceptibles** (proxy de ácido + tag Q informativo). Extensión no-estándar fundamentada. |
| Defectos (taint/fault, conteos por taza) | Booleano `is_defective` + tipo de **lista orientativa no cerrada + campo editable** (el CVA desaconseja catálogos cerrados). Defecto = juicio contextual, vive en el affective. |
| Notas afectivas (JAR, etc.) | Campo de **texto libre sin lógica de negocio**; los tipos del CVA son material de guía de uso. |
| Cupping score (9→100, deducciones) | Lógica de agregación de segundo orden a nivel del café; deducción por defecto/no-uniformidad **abstraída** desde las N evaluaciones (no tazas físicas). Coeficiente exacto pendiente de la calculadora oficial SCA. |
| Vocabulario sensorial | Taxonomía **WCR Sensory Lexicon 2.0** (110 atributos) como fuente de descriptores; ver `wcr-lexico-sensorial-taxonomia.md`. |

**Patrón transversal — señales de confianza del dato:** el sistema guarda no solo la evaluación, sino *cuán confiable es*. Lo expresan, de forma común: método/contexto/producto de evaluación, estado de corroboración (cafetería aporta → especialista corrobora), flag de roasting problem, concordancia inter-especialista, y umbral de validez (mín. 5 evaluaciones + dispersión). Es la columna vertebral de la verificación.

**Fundamentación de validez (cadena conceptual):** hardware/software → validación de especialista (opción C) → inter-subjetividad → concordancia inter-especialista derivada → verificación de afirmaciones. La concordancia es el **sustituto metodológico** del control del cupping (tag "sustituto de las 5 tazas del CVA"), no un proxy de magnitud de la uniformidad del lote.

---

## Concordancia inter-especialista — fuentes para implementar el método

> Bibliografía de *inter-rater reliability* (fiabilidad inter-evaluador) — campo: psicometría / estadística de concordancia (no econometría). Soporta el cálculo y la comunicación por rangos de la concordancia que vive en `Offering`.

**Medidas estándar aplicables:**

- **Kendall's W (coeficiente de concordancia).** Mide el acuerdo global entre *m* evaluadores que puntúan los mismos ítems; rango 0 (sin acuerdo) a 1 (acuerdo perfecto). No paramétrico (robusto para escalas ordinales como 0-15 / 1-9). Estándar en paneles sensoriales y estudios de consenso. Mide concordancia en el **patrón/perfil** (si todos ordenan los ejes de forma similar), no necesariamente en el valor absoluto. Fórmula: W = 12·SS / (m²(n³−n)). Fuente seminal: Kendall & Smith (1939), *The problem of m rankings*, Ann. Math. Stat. 10, 275-287; Kendall (1948), *Rank correlation methods*.
- **ICC (Intraclass Correlation Coefficient).** Estándar para datos continuos y para acuerdo en **valores absolutos** (si todos coinciden en "acidez = 7", no solo en el orden). Marco específico para paneles sensoriales: Bi (2012), *ICC: A Framework for Monitoring and Assessing Performance of Trained Sensory Panels and Panelists*, J. Sensory Studies. Base: Shrout & Fleiss (1979), *Intraclass correlation: Use in assessing rater reliability*, Psychol. Bull. 86.

**Rangos de interpretación (comunicación por categorías) — Cicchetti (1994), guía citada para ICC/kappa:**
- < 0.40 → pobre / baja concordancia
- 0.40 – 0.59 → aceptable (fair)
- 0.60 – 0.74 → buena
- 0.75 – 1.00 → excelente

Estos rangos traducen el coeficiente (0-1) a una etiqueta comunicable intuitivamente al usuario.

**Validación del umbral de 5 evaluaciones:** un estudio de panel sensorial halló que el panel con cinco evaluadores aleatorios exhibió el mayor Kendall's W (W=0.758), coincidiendo con el mínimo de 5 fijado en el diseño (que espeja las 5 tazas del CVA).

**Método elegido para el MVP: Kendall's W** — por implementabilidad (fórmula cerrada única, sin variantes que configurar, no paramétrico). Mide acuerdo en el perfil (qué ejes destacan). Migración a ICC posible si se requiere acuerdo en valores absolutos. Implementación del cálculo en backend (lógica que se almacena).

**Herramientas de referencia:** paquete `irr` de R (funciones `kendall`, `icc`); paquete `psych` (función `ICC`).