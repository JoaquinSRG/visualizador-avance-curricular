# Sprint 3: Materias por semestre y avance del estudiante

**Fechas planeadas:** 30 de septiembre al 6 de octubre de 2026
**Desarrollo real:** 2 y 3 de octubre de 2026
**Retrospectiva:** 3 de octubre de 2026
**Responsable:** Joaquín Serafín Rodríguez González (Puroduct Owner, Scrum Master y Development Team)

## Objetivo del sprint

Mostrar las materias agrupadas por semestre y permitir que el estudiante marque las que ya cursó, guardando ese avance en su cuenta.

## Resultado

| ID    | Historia                                                                                         | SP  | Estado       |
| ----- | ------------------------------------------------------------------------------------------------ | --- | ------------ |
| HU-02 | Lista de materias agrupada en 8 bloques, uno por semestre, con contador                          | 2   | ✅ Terminada |
| HU-03 | Casilla "Cursada" en cada materia, con cambio de color y contadores de avance                    | 3   | ✅ Terminada |
| HU-04 | Tabla `materias_cursadas` y endpoints para marcar y desmarcar; el avance se conserva por usuario | 2   | ✅ Terminada |
| HT-02 | Mensajes de validación en español y 10 pruebas automáticas de login y materias                   | 2   | ✅ Terminada |

**Velocidad del sprint:** 9 de 9 SP (Sprint 2: 11 SP, Sprint 1: 0 SP).
**Backlog restante:** 20 de 40 SP.

**Ajuste de alcance en HT-02:** la confirmación de claves, créditos y prerrequisitos con el plan oficial no se realizó, porque no se consiguió el documento de seriación. Se decidió continuar con los datos provisionales del seeder.

## Retrospectiva

### Qué salió bien

- Se terminaron las cuatro historias comprometidas (9 SP), tres días antes del cierre del sprint.
- Se cumplió el acuerdo de registrar el Daily Scrum en `DAILY.md`.
- Se mantuvo un commit por tarea, identificado con su historia.
- Ya hay pruebas automáticas (`php artisan test`) que cubren el login, la lista de materias y el marcado de cursadas.
- Los mensajes de validación ahora salen en español.

### Qué salió mal

- El trabajo empezó el tercer día del sprint y se concentró otra vez en dos días, aunque el acuerdo era repartirlo en la semana.
- No se consiguió el documento oficial de seriación, así que las claves, los créditos y algunos prerrequisitos siguen siendo provisionales.
- Varios mensajes de commit quedaron con errores de escritura ("crusadas" en lugar de "cursadas").
- El README se subió incompleto en un commit y hubo que corregirlo después.

### Acuerdos para el Sprint 4

1. Repartir HU-05 en al menos cuatro días, porque es la historia más grande del proyecto.
2. Ejecutar `php artisan test` antes de cada push del backend.
3. Escribir los casos de prueba del motor de prerrequisitos al mismo tiempo que se programa.
4. Revisar el mensaje del commit y el archivo completo antes de subirlo.
5. Mantener el seeder como el único lugar con los datos del plan de estudios, para poder corregirlos si se consigue la seriación oficial.

## Plan del Sprint 4 (7 al 13 de octubre)

HU-05, motor de validación de prerrequisitos (8 SP). Propuesta en revisión: adelantar HU-08, despliegue (2 SP), para dejar el sprint en 10 SP.
