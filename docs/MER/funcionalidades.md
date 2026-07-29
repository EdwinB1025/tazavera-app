# Plataforma de verificación de café de especialidad — Resumen

## Idea principal

Un marketplace de dos lados que **verifica la calidad del café** que ofrecen las cafeterías, en lugar de quedarse en su autodescripción comercial. El problema: una cafetería puede afirmar que su café tiene ciertas notas y calidad, pero el consumidor no tiene forma de contrastarlo. La propuesta: aproximar una metodología de evaluación sensorial estándar (CVA de la Specialty Coffee Association), simplificada para que sea usable, y construir la confianza a partir de la **convergencia entre evaluadores independientes**, no de la palabra del vendedor.

## Cómo funciona

Tres tipos de usuario participan sobre un mismo café ofrecido en un punto de venta:

- **La cafetería** declara su café y puede aportar su propia evaluación.
- **Especialistas** (catadores con criterio) evalúan ese café de forma independiente.
- **Consumidores** aportan una valoración simple de gusto.

El sistema calcula el **consenso** (qué perciben los especialistas en promedio) y la **concordancia** (cuánto coinciden entre sí). Cuando hay suficientes evaluaciones que concuerdan, el café pasa a estar **verificado**. Lo que la cafetería declara y lo que perciben los evaluadores se muestran lado a lado: el usuario saca su propia conclusión.

---

## Funcionalidades del MVP (en scope)

- **Registro de cafeterías y su catálogo**, con ubicación en mapa para descubrirlas por cercanía.
- **Ficha de café** con su información de origen y trazabilidad.
- **Captura de evaluaciones** mediante un formulario sensorial simplificado, adaptado a cada tipo de usuario:
  - Especialista: evaluación completa (intensidades, calidad, descriptores de sabor).
  - Consumidor: versión reducida y accesible (gusto general + descriptores básicos).
- **Rueda de sabores interactiva** para seleccionar descriptores de forma visual.
- **Cálculo de consenso y concordancia** a partir de las evaluaciones de especialistas.
- **Estado de verificación** del café (provisional / verificado) según el grado de acuerdo.
- **Vista comparativa**: lo declarado por la cafetería frente al consenso de evaluadores.
- **Valoración de consumidores** contrastable con la de especialistas (calidad técnica vs. gusto popular).

## Backlog (fuera del MVP)

- **Marketplace transaccional**: compra de café (pago, órdenes, comisiones, liquidación a cafeterías).
- **Verificación avanzada de especialistas**: subsistema de autovalidación por cuestionario y reputación, más allá de la certificación declarada.
- **Panel de fidelización** para cafeterías (suscripciones, puntos).
- **Reportes de mercado** para cafeterías (inteligencia agregada sobre qué valoran los evaluadores).
- **Búsqueda geográfica avanzada** (radios, zonas) más allá de la proximidad básica.
- **Evaluación física del café verde** y otras dimensiones del estándar no cubiertas en el MVP.
- **Guía de uso y materiales de calibración** para evaluadores.