# Sprint 7: Más funcionalidades según la retroalimentación

**Fechas planeadas:** 2 al 8 de noviembre de 2026
**Desarrollo real:** 9 y 10 de noviembre de 2026
**Retrospectiva:** 10 de noviembre de 2026
**Responsable:** Joaquín Serafín Rodríguez González (Product Owner, Scrum Master y Development Team)

## Objetivo del sprint

Atender la retroalimentación de la actividad 3.4, que pide más funcionalidades: kárdex del alumno, requisitos para cursar cada materia, materias seriadas, total de créditos y un reporte de la propuesta.

## Resultado

| ID    | Historia                                                                                                        | SP  | Estado       |
| ----- | --------------------------------------------------------------------------------------------------------------- | --- | ------------ |
| HU-12 | Menú por secciones: Plan de estudios, Kárdex, Requisitos y seriación, y Propuesta de inscripción                | 2   | ✅ Terminada |
| HU-13 | Kárdex del alumno con calificación por materia, promedio por semestre y general, créditos cursados y faltantes  | 5   | ✅ Terminada |
| HU-14 | Requisitos para cursar una materia (cumplidos o pendientes) y materias seriadas que se desbloquean con ella     | 3   | ✅ Terminada |
| HU-15 | Reporte imprimible de propuesta de inscripción con las materias disponibles y el total de créditos             | 3   | ✅ Terminada |

**Velocidad del sprint:** 13 de 13 SP. Promedio de los siete sprints: 8.3 SP.
**Backlog:** 58 de 58 SP terminados (100%). Se agregaron 13 SP a los 45 anteriores.
**Pruebas automáticas:** 40, todas pasan (13 nuevas).
**Funcionalidades del sitio:** 11 (antes 7).

**Cambios técnicos:**

- Migración nueva que agrega la columna `calificacion` (0 a 100, opcional) a `materias_cursadas`.
- Endpoints nuevos: `GET /api/kardex`, `PATCH /api/materias/{id}/calificacion`, `GET /api/materias/{id}/seriacion` y `GET /api/propuesta`.
- Componentes nuevos de React: `Kardex`, `Seriacion` y `Propuesta`. El plan de estudios tiene un enlace "Ver requisitos y seriación" en cada materia.
- Al imprimir el kárdex o la propuesta se ocultan el menú y los botones.

## Limitaciones conocidas

- Siguen las del Sprint 6: SQLite en el plan gratuito de Render (las cuentas se pueden perder al reiniciar) y el servidor que tarda en despertar.
- La propuesta toma las materias disponibles en orden de semestre; no considera horarios ni cupo.
- Las claves, los créditos y algunos prerrequisitos siguen siendo provisionales.

## Retrospectiva

### Qué salió bien

- Se atendió la retroalimentación con cuatro historias y el sitio pasó de 7 a 11 funcionalidades.
- El trabajo se repartió en dos días (9 y 10 de noviembre) y no en uno solo, como en los sprints anteriores.
- Las 27 pruebas anteriores siguieron pasando y se agregaron 13 nuevas.
- Cada historia tuvo su propio commit, tanto en el backend como en el frontend.
- Al probar en el navegador se encontraron dos errores pequeños que se corrigieron antes del commit: la fecha del reporte salía en UTC y un aviso de error no se borraba.

### Qué salió mal

- El sprint empezó siete días tarde, igual que los anteriores.
- No se registró el Daily Scrum durante el sprint; se completó al final.
- Hubo errores de manejo de archivos: una migración duplicada (se borró antes de aplicarla), el código de un controlador pegado en `routes/api.php` (se recuperó con `cp`) y el lint corrido desde la carpeta equivocada.

### Acuerdos

1. Revisar en la barra de Neovim el nombre del archivo antes de guardar.
2. Antes de un comando de `npm` o `artisan`, confirmar con `pwd` que se está en la carpeta correcta.
3. Seguir corriendo `php artisan test` y `npm run lint` antes de cada push.
4. Registrar el Daily Scrum el mismo día en que se trabaja.
