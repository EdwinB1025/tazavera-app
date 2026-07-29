# Proyecto MVC Laravel — Dos Ideas de Producto

> Documento de definición de proyecto. Ambas ideas se conciben como productos escalables con vocación de proyecto real, no solo como entregable académico. El alcance se separa explícitamente entre **MVP** (lo mínimo viable y demostrable) y **backlog** (evolución planificada).

---

## Idea 1: Constructor Visual de Transformaciones de Datos

### 0. Motivación y justificación

**Por qué este proyecto.** En proyectos de implementación de software de logística, la integración entre sistemas (por ejemplo un WMS y un ERP) exige configurar middleware a medida: manejo de mensajes, transformación de formatos, mapeo de campos y soporte de distintos protocolos. Ese trabajo es manual, técnico y lento, y representa una parte significativa del coste de cada implementación.

**Por qué lo elijo.** Surge de mi experiencia profesional como consultor de implementación, donde he visto este cuello de botella de forma recurrente. La intención no es solo cumplir con la actividad académica, sino desarrollar un producto real y escalable. A nivel técnico, busca consolidar arquitectura limpia en Laravel (interfaces, inyección de dependencias, patrón Strategy), diseño orientado a la extensibilidad y comprensión profunda de sistemas de mensajería.

### 1. Problema y usuario objetivo

**Problema.** Configurar los mapeos de integración entre dos sistemas es manual, técnico y costoso en horas. Cada integración nueva reabre el mismo trabajo de transformación y validación de datos.

**Usuario objetivo.** Primario: el **consultor de implementación con conocimiento técnico mínimo** — alguien que entiende el dominio de integración (sabe qué es mapear un campo) pero no necesariamente programa. La interfaz debe ser visual y guiada, sin escribir código, pero exponiendo los conceptos de integración que el consultor sí maneja (campos, formatos, reglas de validación). Secundario y futuro: perfiles menos técnicos del lado del cliente.

### 2. Propuesta de valor

Reducir el coste de implementación quitando complejidad a la capa de integración. En lugar de configurar middleware a medida en cada proyecto, el consultor construye mapeos de forma visual y los reutiliza como plantillas.

**Posicionamiento.** El producto se sitúa en el punto intermedio entre el script manual y la iPaaS empresarial: para el consultor de logística que no puede justificar el coste de una plataforma como MuleSoft pero necesita más que código a medida.

**Frente a alternativas del mercado.** Las plataformas iPaaS (MuleSoft, Boomi y similares) cubren el mismo núcleo funcional —conectores pre-construidos, mapeo y transformación de datos, orquestación de flujos, gestión de APIs y monitoreo— pero están diseñadas para grandes empresas con equipos de integración dedicados. La diferenciación **no** está en igualar funcionalidades (no se puede ganar ahí), sino en tres ejes:

- **Coste y barrera de entrada.** Las iPaaS operan con contratos anuales sin precio de lista público (rangos típicos de decenas a cientos de miles al año); MuleSoft es entre 50% y 100% más caro que Boomi. Una herramienta enfocada, de precio transparente y bajo, para un nicho que no puede pagar esas cifras es un hueco real.
- **Diseñado desde cero para el no-técnico.** Incluso Boomi, la más accesible, sigue siendo developer-centric y presenta fricción para usuarios no técnicos. El producto se diseña *desde el origen* para el consultor de bajo perfil técnico, no adaptado a posteriori.
- **Especialización vertical en logística.** Las generalistas tratan todos los sistemas por igual. Un especialista vertical gana en tiempo-a-valor dentro de su nicho (el caso de Celigo en NetSuite lo demuestra: de semanas a días). El foco en logística —sistemas WMS↔ERP y formatos del sector ya modelados— ofrece valor desde el día uno donde una herramienta universal exige configuración.

**Biblioteca reutilizable cross-cliente (modelo de dos capas).** El diferenciador central a medio plazo es separar lo que es reutilizable de lo que se customiza:

- *Capa 1 — Definición del sistema destino:* la estructura y campos de un ERP/WMS concreto son iguales en todos los clientes que usan ese sistema. Se modela una vez y se reutiliza. El trabajo costoso de integración (descubrir y modelar el destino) se resuelve una sola vez.
- *Capa 2 — Plantilla de traducción:* el mapeo entre el origen de *este* cliente y la estructura destino ya conocida. Siempre se customiza por cliente, pero parte con ventaja porque el destino ya está catalogado.

Que sea reutilizable no la hace no customizable: cada plantilla se customiza, pero sobre una estructura destino que ya existe. Frente a la biblioteca genérica y masiva de las iPaaS, la ventaja aquí es la **profundidad vertical**: pocas definiciones excelentes para los sistemas que de verdad importan en logística, no millones de integraciones genéricas.

### 3. Funcionalidades: MVP vs. backlog

**MVP**

- Constructor visual de mapeos de interfaz entre sistemas.
- **Modelo de plantillas en dos capas:**
  - *Definición del sistema destino* — creada por el consultor en primer uso y guardada para reutilizar. Lleva un campo de propiedad (`organization_id`): **privada por organización** en el MVP.
  - *Plantilla de traducción* — customizada por cliente sobre una definición de sistema destino existente.
- Configuración de salida multi-formato: XLS, CSV, JSON, longtext con caracteres hexadecimales.
- Lógicas de validación y transformación de datos asociables a cada plantilla.
- Edición/corrección de una definición de sistema existente (para arreglar definiciones incompletas).
- Motor de traducción que aplica una plantilla a un mensaje entrante y produce el mensaje de salida.
- Abstracción de broker (`BrokerInterface`) con **una** implementación concreta (RabbitMQ local).
- Manejo de cola mínimo: una cola de entrada, **un solo consumidor secuencial** (`prefetch=1`), ack tras traducción exitosa, log en caso de fallo.
- Modelo de plantilla con campo `partition_key` declarado (inerte en el MVP, preparado para el modo paralelo).
- Diseño extensible preparado para implementaciones AI-agénticas (interfaces/contratos listos, **no** implementado).

**Backlog**

- **Catálogo compartido de definiciones de sistema** (cross-organización): promover una definición privada a una biblioteca global, con revisión/validación. Aquí emerge el efecto red y el foso competitivo.
- **Herencia de plantillas** (base + override por cliente) si surgen patrones repetidos en el lado origen.
- Modo paralelo delegado al broker (consistent hash exchange + colas-partición + single active consumer), activado vía la `partition_key` ya modelada.
- Reintentos con límite e idempotencia en el procesamiento.
- Dead-letter queue para mensajes que fallan definitivamente.
- Cola de salida para desacoplar al sistema destino cuando lo requiera.
- Módulo de configuración para apuntar a brokers gestionados de terceros (CloudAMQP, Amazon MQ) vía su API/conexión.
- Dashboard de monitoreo del estado de la cola (qué se procesó, qué falló).
- Implementación efectiva de la capa AI-agéntica sobre los contratos definidos.

### 4. Riesgos, escalabilidad y decisiones diferidas

**Arquitectura de integración (flujo).**

```
Sistema A → [cola de entrada, con partition_key] → APP (plantilla: mapeo + validación + transformación) → entrega a Sistema B
```

La app **es** el motor de traducción (el middleware de integración para este caso), no una capa sobre un middleware externo. Entrada y salida pueden ser dos exchanges/colas del mismo broker; no requieren instalaciones separadas. El broker de salida solo se justifica cuando el sistema destino necesita desacople, por lo que se difiere al backlog.

**Delegación de concurrencia al broker.** El paralelismo con garantía de orden se delega a la infraestructura, no se implementa en la app. El broker enruta por `partition_key`: todos los mensajes con la misma clave (p. ej. `client_id`) van a la misma cola y se procesan en orden, mientras claves distintas corren en paralelo. La app no contiene código de concurrencia; solo declara la clave de partición al publicar. El cambio entre modo secuencial y paralelo es **un argumento** en la publicación (`publish(message, partitionKey)`), cero cambios en el consumidor o en la lógica de traducción.

**Análisis de requisitos por modo (ejercicio de escalabilidad).**

- *Modo secuencial (MVP):* una cola, un consumidor, `prefetch=1`, ack tras traducción.
- *Modo paralelo (backlog, pre-analizado):* número de particiones a fijar temprano y con margen (cambiarlo después implica re-hashear); `partition_key` por plantilla; `prefetch` por consumidor; RabbitMQ 4.0+ (por el fallo de arranque con single active consumer en versiones previas).

**Riesgos y decisiones diferidas.**

- **[Certain] RabbitMQ no rebalancea consumidores automáticamente.** A diferencia de Kafka, añadir/quitar consumidores exige gestión manual. Si el rebalanceo manual se convierte en el cuello de botella a escala, se documenta la **migración a Kafka** como ruta de evolución (rebalanceo nativo, a cambio de mayor coste operativo).
- **[Certain] El worker es un proceso de larga duración.** Requiere Supervisor (o systemd) para mantenerse vivo en cualquier despliegue real. Dependencia de entorno desde el MVP.
- **[Certain] Durabilidad de mensajes** activada desde el MVP: perder mensajes en un reinicio del broker es inaceptable incluso en pruebas.
- **[Certain] Credenciales del broker vía variables de entorno** (manejadas por el cliente AMQP, no por lógica propia); **TLS (`amqps://`)** para conexiones a proveedores gestionados; gestión de secretos (Vault/Secrets Manager) como evolución futura.
- **[Likely] Dependencia de entorno de desarrollo:** RabbitMQ es un servicio Erlang independiente de XAMPP/PHP; debe instalarse y arrancarse por separado junto al worker.
- **[Guessing] Sobre-diseño del particionado:** el número de particiones y la clave óptima dependen de mensajes reales aún inexistentes. Se fijan valores iniciales razonables marcados como provisionales hasta tener tráfico real.

---

## Idea 2: Two-Sided Marketplace de Specialty Coffee

### 0. Motivación y justificación

**Por qué este proyecto.** El mercado de specialty coffee sufre un sesgo de evaluación: muchos establecimientos venden café como "specialty" sin serlo, y los consumidores no especializados carecen de criterios para distinguirlo. Esto distorsiona el mercado. Existe un hueco para una plataforma que combine evaluación rigurosa (de especialistas) con accesible (de consumidores) y la conecte con descubrimiento y comercio.

**Por qué lo elijo.** Motivación personal: provengo de una región colombiana productora de café de especialidad y llevo años investigando el tema. La intención es construir un producto real y escalable, con un modelo de negocio de dos lados (two-sided marketplace).

### 1. Problema y usuario objetivo

**Problema.** Existe café "specialty" no auténtico en el mercado y los consumidores no saben evaluarlo. La calificación queda en manos de pocos, generando sesgo sobre qué se considera de calidad que incentiva la inflacion inecesaria de precios.

**Usuarios.**

- **Primario (paga): las cafeterías (B2B).** Pagan por los beneficios comerciales: datos y tendencias de mercado, gestión de fidelización (suscripciones, puntos) y visibilidad en el mapa.
- **Secundario (no paga suscripción): consumidores (B2C).** Califican el café y compran. Generan ingresos por **comisión en transacciones**, no por suscripción.

Es explícitamente un **two-sided marketplace**: los ratings son la señal que atrae a ambos lados.

### 2. Propuesta de valor

**Para el consumidor:** descubrir cafeterías de specialty coffee verificadas por una comunidad con evaluación dual (simple y especializada), reduciendo el sesgo del mercado.

**Para la cafetería:** acceso a inteligencia de mercado ("qué es bueno para tu negocio"), herramientas de fidelización y un canal de venta directa. La plataforma está en posición de analizar el mercado y asesorar a la cafetería gracias a los datos de rating agregados.

**Frente a alternativas:** las apps de reseñas genéricas no distinguen el conocimiento del evaluador. El diferenciador es la **evaluación dual con validación de especialistas** basada en estándares reales de la industria del café.

### 3. Funcionalidades: MVP vs. backlog

**MVP**

- Mapa/listado de cafeterías de specialty coffee por región.
- **Sistema de rating dual:**
  - Consumidor normal: feedback simplificado (versión accesible del procedimiento de evaluación de la Specialty Coffee Association).
  - Especialista: plantilla técnica de evaluación (basada en el estándar Q Grader / SCA).
- **Validación de especialista (opción C):** el usuario declara su certificación Q Grader en el perfil **y** existe la vía de auto-validación mediante exposición/cuestionario con calificación de la comunidad. Permite reconocer a baristas o catadores capaces sin certificación formal.
- **Panel de cafetería:** gestión de fidelización (suscripciones, puntos).
- **Marketplace con transacciones (modelo simplificado):** el usuario compra café (takeaway, beans); el pago va a una **cuenta intermediaria de la plataforma** (no directo a la cafetería); la transacción se registra y es visible en un reporte para la cafetería; el payout a la cafetería es manual o por lotes.
- Comunicación de órdenes a la cafetería; la cafetería responde/confirma.

**Backlog**

- **Payout automatizado** a cafeterías (settlement programado en lugar de manual/batch).
- **Reportes de tendencias de mercado** para cafeterías (inteligencia de mercado a partir de los datos de rating).
- **Tercer lado del mercado — especialistas monetizados:** marketplace de servicios donde los especialistas venden consultoría, recetas para cafeterías, patentes de bebidas a base de café, y **experiencias/workshops de cata** (mercado ya validado, por ejemplo en Barcelona). Mecanismo de incentivo para aumentar su participación.
- **Integración logística con terceros:** APIs de proveedores de delivery (globales o locales) para orquestar entregas automáticamente, en lugar del mecanismo manual del MVP. La cafetería sigue siendo responsable del fulfillment; la app integra el servicio de entrega.

### 4. Riesgos, escalabilidad y decisiones diferidas

- **[Certain] Pagos en el MVP implican complejidad real.** Procesador de pagos (compliance de datos sensibles), registro auditable de transacciones y gestión de disputas (reclamos por no-entrega). El **modelo simplificado** (cuenta intermediaria + payout manual/batch) es la decisión deliberada para reducir la complejidad de settlement y compliance en el MVP; la automatización del payout se difiere al backlog.
- **[Likely] Decisiones de producto/negocio pendientes de fijar antes de construir pagos:** elección de procesador (Stripe es estándar en Latam, con particularidades por país), frecuencia de settlement, quién absorbe el riesgo de fraude/no-entrega, y estructura de comisión (fija vs. porcentaje).
- **[Likely] Gaming de la validación de especialista.** La auto-validación + calificación comunitaria puede manipularse. Mitigación: combinar verificación de certificación Q Grader (donde exista) con reputación acumulada y revisión. La validación contra una base de datos externa de la SCA, si fuera viable, queda como integración de backlog.
- **[Likely] Problema del arranque en marketplaces de dos lados (cold start).** Sin cafeterías no hay consumidores y viceversa. Riesgo de negocio a documentar: estrategia de arranque por región concentrada (densidad local antes que expansión).
- **[Guessing] Calidad y consistencia del dato de rating.** Si los volúmenes de evaluación son bajos al inicio, las recomendaciones a cafeterías podrían no ser estadísticamente sólidas. Marcar como provisional hasta tener masa crítica de ratings.

---

> **Nota sobre el bloque 4:** en ambas ideas se completa de forma incremental a medida que se construye. Las etiquetas **[Certain]**, **[Likely]** y **[Guessing]** distinguen hechos confirmados, inferencias fuertes y supuestos a validar.