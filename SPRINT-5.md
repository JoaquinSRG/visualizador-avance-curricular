# Sprint 5: Filtro por estado, pruebas de escenarios y despliegue

**Fechas planeadas:** 17 al 23 de octubre de 2026
**Desarrollo real:** 24 de octubre de 2026
**Retrospectiva:** 24 de octubre de 2026
**Responsable:** Joaquín Serafín Rodríguez González (Product Owner, Scrum Master y Development Team)

## Objetivo del sprint

Que el estudiante pueda filtrar sus materias por estado, comprobar la aplicación en distintos escenarios de avance y tener el sitio completo funcionando en línea.

## Resultado

| ID    | Historia                                                                                   | SP  | Estado       |
| ----- | ------------------------------------------------------------------------------------------ | --- | ------------ |
| HU-08 | Backend publicado en Render (Docker, plan gratuito) y conectado con el sitio de GitHub Pages | 2   | ✅ Terminada |
| HU-06 | Filtro por estado: Todas, Disponibles, Bloqueadas y Cursadas, con contador en cada botón   | 3   | ✅ Terminada |
| HU-07 | Pruebas de escenarios (`EscenariosTest.php`): estudiante nuevo, a la mitad y carrera completa | 5   | ✅ Terminada |

**Velocidad del sprint:** 10 de 10 SP (Sprint 4: 10, Sprint 3: 9, Sprint 2: 11, Sprint 1: 0). Promedio: 8 SP.
**Backlog:** 40 de 40 SP terminados (100%).
**Pruebas automáticas:** 25 propias y 27 en total, todas pasan.

## Retrospectiva

### Qué salió bien

- Se terminaron las tres historias y el backlog quedó completo.
- El sitio funciona completo en línea: backend en Render y frontend en GitHub Pages.
- Las pruebas de escenarios se escribieron junto con el código y `php artisan test` se ejecutó antes del push.
- Cada historia de código se subió en su propio commit.

### Qué salió mal

- El sprint empezó siete días tarde y todo el trabajo se hizo en un solo día.
- No hubo Daily Scrum del 17 al 23 de octubre.
- HU-08 quedó para el final y no al inicio, como se había acordado.

### Acuerdos para el Sprint 6

1. Trabajar al menos una hora diaria y registrar el Daily Scrum aunque no haya avance.
2. Empezar cada sprint por la historia que depende de algo externo, como el despliegue.
3. Mantener las pruebas automáticas y ejecutarlas antes de cada push.
4. Probar el sitio en línea después de cada cambio importante.

## Plan del Sprint 6 (25 al 31 de octubre)

Sprint de ajustes de 5 SP: arranque del servidor sin borrar la base de datos, aviso de servidor despertando y documentación final.
