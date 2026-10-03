# Visualizador de Avance Curricular

Aplicación web para que los estudiantes de la Licenciatura en Desarrollo de Sistemas Web (UDG Virtual) visualicen su avance dentro de la malla curricular: marcan las materias que ya cursaron y el sistema les muestra cuáles están disponibles para inscribir según el cumplimiento de sus prerrequisitos.

Proyecto de la materia **Proyecto VII**, desarrollado con la metodología **Scrum**.

## Stack

- **Frontend:** React 19 + Vite
- **Backend:** Laravel 13 (API REST) + Laravel Sanctum (autenticación por token)
- **Base de datos:** SQLite

## Requisitos

- PHP 8.3 o superior y Composer
- Node.js 20 o superior y npm

## Cómo correr el proyecto

### Backend

```bash
cd backend
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate --seed
php artisan serve
```

La API queda en `http://127.0.0.1:8000/api`.

### Frontend

En otra terminal:

```bash
cd frontend
npm install
npm run dev
```

La app queda en `http://localhost:5173`.

### Usuario de prueba

- Correo: `test@example.com`
- Contraseña: `password`

### Pruebas automáticas

```bash
cd backend
php artisan test
```

Las pruebas usan una base de datos SQLite en memoria, por lo que no modifican los datos del proyecto. Cubren el registro, el inicio de sesión, la lista de materias y el marcado de materias cursadas.

## Endpoints de la API

| Método | Ruta                         | Protegida | Descripción                                                            |
| ------ | ---------------------------- | --------- | ---------------------------------------------------------------------- |
| POST   | `/api/register`              | No        | Crea un usuario y regresa su token                                     |
| POST   | `/api/login`                 | No        | Inicia sesión y regresa un token                                       |
| GET    | `/api/me`                    | Sí        | Regresa el usuario de la sesión                                        |
| POST   | `/api/logout`                | Sí        | Cierra la sesión (borra el token)                                      |
| GET    | `/api/materias`              | Sí        | Lista las materias con sus prerrequisitos y si el usuario ya las cursó |
| POST   | `/api/materias/{id}/cursada` | Sí        | Marca una materia como cursada para el usuario de la sesión            |
| DELETE | `/api/materias/{id}/cursada` | Sí        | Quita la marca de cursada                                              |

## Estructura

```
backend/    API en Laravel (modelos, migraciones, seeders, controladores, pruebas)
frontend/   Interfaz en React (login, registro, materias por semestre y avance)
DAILY.md    Registro del Daily Scrum
SPRINT-2.md Resumen del Sprint 2 y de su retrospectiva
SPRINT-3.md Resumen del Sprint 3 y de su retrospectiva
```

## Avance del Product Backlog

| ID    | Historia                                                | SP  | Sprint | Estado       |
| ----- | ------------------------------------------------------- | --- | ------ | ------------ |
| HT-01 | Base del backend: migraciones, modelos y seeder         | 3   | 2      | ✅ Terminada |
| HU-09 | Registro e inicio de sesión                             | 5   | 2      | ✅ Terminada |
| HU-01 | Ver la lista completa de materias                       | 3   | 2      | ✅ Terminada |
| HU-02 | Agrupar materias por semestre                           | 2   | 3      | ✅ Terminada |
| HU-03 | Marcar materias como cursadas                           | 3   | 3      | ✅ Terminada |
| HU-04 | Guardar el avance del estudiante                        | 2   | 3      | ✅ Terminada |
| HT-02 | Mensajes de validación en español y pruebas automáticas | 2   | 3      | ✅ Terminada |
| HU-05 | Motor de validación de prerrequisitos                   | 8   | 4      | Pendiente    |
| HU-06 | Ver materias disponibles y bloqueadas                   | 5   | 5      | Pendiente    |
| HU-07 | Pruebas en distintos escenarios de avance               | 5   | 5      | Pendiente    |
| HU-08 | Despliegue en un enlace web                             | 2   | 5      | Pendiente    |

Avance: 20 de 40 story points terminados.

Nota: las claves de las materias, los créditos y algunos prerrequisitos son provisionales, porque no se cuenta con el documento oficial de seriación. Todos esos datos viven en `backend/database/seeders/MateriaSeeder.php`.

## Autor

Joaquín Serafín Rodríguez González, Licenciatura en Desarrollo de Sistemas Web, UDG Virtual.
