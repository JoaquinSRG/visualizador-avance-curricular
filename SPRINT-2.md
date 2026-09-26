# Sprint 2: Base del backend, login y lista de materias

**Fechas planeadas:** 23 al 29 de septiembre de 2026
**Desarrollo real:** 25 y 26 de septiembre de 2026
**Retrospectiva:** 26 de septiembre de 2026
**Responsable:** Joaquín Serafín Rodríguez González (Product Owner, Scrum Master y Development Team)

## Objetivo del sprint

Tener la base técnica del nuevo stack funcionando: API en Laravel con SQLite, registro e inicio de sesión, y la lista de materias mostrada en React.

## Resultado

| ID | Historia | SP | Estado |
|---|---|---|---|
| HT-01 | Migraciones de `materias` y `prerrequisitos`, modelo `Materia` con relaciones y seeder con las 44 materias de la LDSW | 3 | ✅ Terminada |
| HU-09 | Registro, login y logout con Sanctum, más pantallas en React con sesión persistente | 5 | ✅ Terminada |
| HU-01 | Endpoint `GET /api/materias` y lista de materias en React con sus prerrequisitos | 3 | ✅ Terminada |

**Velocidad del sprint:** 11 de 11 SP (Sprint 1: 0 SP).
**Backlog restante:** 27 de 38 SP.

## Retrospectiva

### Qué salió bien
- Se terminaron las tres historias comprometidas (11 SP).
- Se cumplió el acuerdo del Sprint 1 de hacer un commit por tarea: 8 commits identificados por historia.
- El seeder usa el plan de estudios real de la carrera (44 materias).
- Cada endpoint se probó con `curl` antes de conectarlo con React.

### Qué salió mal
- El trabajo empezó dos días tarde y se concentró en sesiones largas de noche.
- Errores pequeños de escritura costaron tiempo: una columna mal nombrada, un `;` faltante y el seeder sin registrar.
- No se registró el Daily Scrum, aunque era un acuerdo del Sprint 1.
- Las claves, los créditos y algunos prerrequisitos del plan de estudios son provisionales.
- Los mensajes de validación de Laravel salen en inglés.

### Acuerdos para el Sprint 3
1. Repartir el trabajo durante toda la semana, con al menos una sesión corta al día.
2. Registrar el Daily Scrum en `DAILY.md` (qué hice, qué haré, qué me bloquea).
3. Tarea técnica HT-02: traducir los mensajes de validación al español y confirmar claves, créditos y prerrequisitos con el plan oficial.
4. Agregar pruebas automáticas (`php artisan test`) para login y materias antes de empezar el motor de prerrequisitos.
5. Mantener un commit por tarea.

## Plan del Sprint 3 (30 de septiembre al 6 de octubre)

HU-02 (2 SP), HU-03 (3 SP), HU-04 (2 SP) y HT-02 (2 SP): 9 SP.
