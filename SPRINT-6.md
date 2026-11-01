# Sprint 6: Ajustes finales y documentación

**Fechas planeadas:** 25 al 31 de octubre de 2026
**Desarrollo real:** 1 de noviembre de 2026
**Retrospectiva:** 1 de noviembre de 2026
**Responsable:** Joaquín Serafín Rodríguez González (Product Owner, Scrum Master y Development Team)

## Objetivo del sprint

Mejorar la confiabilidad del sitio en línea, avisarle al estudiante cuando el servidor está arrancando y cerrar la documentación del proyecto.

## Resultado

| ID    | Historia                                                                                                   | SP  | Estado       |
| ----- | ---------------------------------------------------------------------------------------------------------- | --- | ------------ |
| HT-03 | Arranque del servidor con `migrate --force` y `db:seed --force`; el seeder ya no duplica al usuario de prueba | 1   | ✅ Terminada |
| HU-11 | Aviso en inicio de sesión y registro cuando la respuesta tarda más de 4 segundos (`useAvisoLento`)          | 2   | ✅ Terminada |
| HT-04 | Documentación final: `SPRINT-5.md`, `SPRINT-6.md`, `PRUEBAS.md`, `DAILY.md` y README                       | 2   | ✅ Terminada |

**Velocidad del sprint:** 5 de 5 SP. Promedio de los seis sprints: 7.5 SP.
**Backlog:** 45 de 45 SP terminados (100%). Se agregaron 5 SP de ajustes a los 40 originales.
**Pruebas automáticas:** 27, todas pasan.

**Cambios al backlog:**

- Se agregaron HT-03, HU-11 y HT-04, propuestos en la retrospectiva del Sprint 5.
- La corrección con datos oficiales del plan de estudios no entró, porque sigue sin conseguirse el documento oficial de seriación.

## Limitaciones conocidas

- El plan gratuito de Render no guarda archivos entre reinicios. Como la base de datos es SQLite, las cuentas registradas se pierden cuando el servicio se reinicia. Se decidió mantener SQLite y documentarlo.
- El servidor se apaga cuando no se usa, y la primera petición puede tardar hasta un minuto. Para eso está el aviso de HU-11.
- Las claves, los créditos y algunos prerrequisitos siguen siendo provisionales.

## Retrospectiva

### Qué salió bien

- Se terminaron las tres historias y no se rompió nada: las 27 pruebas siguen pasando.
- `npm run lint` detectó un error de escritura en una variable antes de subir el código; `npm run build` no lo había marcado.
- Cada cambio se probó en local antes del push y después en el sitio en línea.

### Qué salió mal

- El sprint empezó siete días tarde y se hizo en un solo día, igual que los anteriores.
- No hubo Daily Scrum del 25 al 31 de octubre.
- `SPRINT-5.md` y el Daily del Sprint 5 no se habían subido al repositorio.

### Acuerdos

1. Correr `npm run lint` además de `php artisan test` antes de cada push.
2. Subir la documentación del sprint el mismo día de la retrospectiva.
3. Registrar el Daily Scrum todos los días, aunque no haya avance.
4. Si se consigue el documento oficial de seriación, corregir el seeder y las pruebas en un sprint de mantenimiento.
