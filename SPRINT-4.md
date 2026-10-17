# Sprint 4: Motor de prerrequisitos y resumen del avance

**Fechas planeadas:** 7 al 13 de octubre de 2026
**Desarrollo real:** 16 de octubre de 2026
**Retrospectiva:** 16 de octubre de 2026
**Responsable:** Joaquín Serafín Rodríguez González (Product Owner, Scrum Master y Development Team)

## Objetivo del sprint

Que la aplicación le diga al estudiante qué materias puede inscribir según los prerrequisitos que ya cumplió, y mostrarle un resumen de su avance.

## Resultado

| ID    | Historia                                                                                                                                        | SP  | Estado       |
| ----- | ----------------------------------------------------------------------------------------------------------------------------------------------- | --- | ------------ |
| HU-05 | Motor de prerrequisitos (`app/Services/MotorPrerrequisitos.php`): estado de cada materia, materias faltantes y validación al marcar y desmarcar | 8   | ✅ Terminada |
| HU-10 | Endpoint `GET /api/resumen` y panel "Mi avance" con porcentaje, créditos y materias para inscribir                                              | 2   | ✅ Terminada |

**Velocidad del sprint:** 10 de 10 SP (Sprint 3: 9 SP, Sprint 2: 11 SP, Sprint 1: 0 SP).
**Backlog:** 30 de 40 SP terminados (75%).
**Pruebas automáticas:** 20 (10 del Sprint 3 y 10 nuevas de prerrequisitos y resumen).

**Cambios al backlog:**

- Se agregó HU-10, resumen del avance (2 SP), porque la retroalimentación del Sprint 3 pide que el sitio tenga al menos 5 funcionalidades.
- HU-06 se reestimó de 5 a 3 SP, porque el estado Disponible o Bloqueada de cada materia ya se muestra desde HU-05. Lo que queda es el filtro por estado.
- HU-08 (despliegue) regresa al Sprint 5: el frontend ya está en GitHub Pages, pero el backend en Render no se terminó.

## Funcionalidades del sitio al cierre del sprint

1. Registro e inicio de sesión.
2. Plan de estudios por semestre.
3. Marcar materias cursadas y guardar el avance.
4. Validación de prerrequisitos (Cursada, Disponible o Bloqueada, con lo que falta).
5. Resumen del avance.

## Retrospectiva

### Qué salió bien

- Se terminó HU-05, la historia más grande del proyecto, y el sitio ya cumple su objetivo principal.
- Las pruebas del motor se escribieron junto con el código y `php artisan test` se ejecutó antes de cada push.
- El motor lee los prerrequisitos de la base de datos; el seeder sigue siendo el único lugar con los datos del plan.
- Se atendió la retroalimentación del Sprint 3 con una quinta funcionalidad.

### Qué salió mal

- El sprint empezó nueve días tarde y todo el trabajo se hizo en un solo día. El acuerdo de repartir HU-05 en cuatro días no se cumplió.
- No hubo Daily Scrum del 7 al 15 de octubre.
- HU-08 no avanzó: el backend sigue sin publicarse y el sitio en línea todavía no puede iniciar sesión.

### Acuerdos para el Sprint 5

1. Trabajar al menos una hora diaria y registrar el Daily Scrum aunque no haya avance.
2. Empezar el sprint con HU-08 para tener el sitio completo en línea lo antes posible.
3. Mantener `php artisan test` antes de cada push y agregar pruebas por cada escenario de HU-07.
4. Revisar la lista de funcionalidades al cerrar cada sprint para no bajar de cinco.

## Plan del Sprint 5 (17 al 23 de octubre)

HU-08 despliegue del backend (2 SP), HU-06 filtro por estado (3 SP) y HU-07 pruebas de escenarios (5 SP): 10 SP.
