# Reporte de pruebas

Las pruebas se ejecutan con `php artisan test` dentro de `backend` y usan una base de datos SQLite en memoria. Resultado al cierre del Sprint 7 (10 de noviembre de 2026): **40 pruebas, todas pasan**.

## Pruebas de escenarios de avance (HU-07)

Archivo: `backend/tests/Feature/EscenariosTest.php`

| Escenario                | Prueba                                                                  | Qué comprueba                                                                                         |
| ------------------------ | ----------------------------------------------------------------------- | ----------------------------------------------------------------------------------------------------- |
| Estudiante nuevo         | `estudiante_nuevo_solo_tiene_disponibles_las_materias_sin_prerrequisitos` | Solo están disponibles las materias sin prerrequisitos, las demás están bloqueadas y el resumen está en cero |
| A la mitad de la carrera | `estudiante_a_la_mitad_de_la_carrera_tiene_estados_congruentes`         | Con los primeros cuatro semestres cursados, cada materia siguiente está disponible o bloqueada según sus prerrequisitos, y el resumen es correcto |
| Carrera completa         | `carrera_completa_no_deja_materias_disponibles_ni_bloqueadas`           | Las 44 materias están cursadas, el porcentaje es 100 y los créditos cursados son iguales a los totales |
| Recorrido completo       | `se_puede_completar_la_carrera_marcando_materia_por_materia`            | El sistema acepta marcar las 44 materias una por una, en orden de semestre                            |
| Regreso a cero           | `al_desmarcar_toda_la_carrera_se_regresa_al_escenario_inicial`          | Al desmarcar de la última materia a la primera, el avance regresa a cero                              |

## Resto de las pruebas

| Archivo                   | Pruebas | Qué cubre                                                                                      |
| ------------------------- | ------- | ---------------------------------------------------------------------------------------------- |
| `AuthTest.php`            | 5       | Registro, correo repetido, inicio de sesión, contraseña incorrecta y rutas protegidas sin token |
| `MateriasTest.php`        | 5       | Lista de materias, marcar y desmarcar, y que el avance de un estudiante no afecte a otro        |
| `PrerrequisitosTest.php`  | 7       | Estados disponible, bloqueada y cursada, validación al marcar y desmarcar, y cadenas de prerrequisitos |
| `ResumenTest.php`         | 3       | Resumen protegido, resumen en cero y conteo de materias y créditos                             |
| `KardexTest.php`          | 6       | Kárdex protegido, kárdex vacío, calificaciones, créditos y promedio, guardar calificación, no calificar materias sin cursar y rango de 0 a 100 |
| `SeriacionTest.php`       | 3       | Seriación protegida, requisitos cumplidos y pendientes, y materias que se desbloquean          |
| `PropuestaTest.php`       | 4       | Propuesta protegida, primero las materias de semestres bajos, sin cursadas ni bloqueadas, y rango de 1 a 8 materias |
| `ExampleTest.php` (x2)    | 2       | Pruebas de ejemplo que trae Laravel                                                            |
