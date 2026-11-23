# API REST - Gestión de Academias

Proyecto final del módulo profesional de **Desarrollo Web en Entorno Servidor (DAW)**.

API REST desarrollada con Laravel para gestionar academias, cursos y estudiantes mediante peticiones HTTP y respuestas en formato JSON.

## Tecnologías utilizadas

- PHP 8.3 o superior
- Laravel 13
- MySQL 8.4
- Laravel Sanctum
- Docker y Laravel Sail
- Postman

## Instalación y ejecución

### 1. Requisitos previos

Antes de comenzar, es necesario tener instalados:

- Git
- PHP y Composer
- Docker Desktop o Docker Engine con Docker Compose

### 2. Clonar el repositorio

```bash
git clone https://github.com/jgaragu1705/proyecto_final_API.git
cd proyecto_final_API
```

### 3. Configurar el entorno

Crear el archivo `.env` a partir del archivo de ejemplo:

```bash
cp .env.example .env
```

### 4. Instalar las dependencias

```bash
composer install
```

### 5. Iniciar los contenedores

```bash
./vendor/bin/sail up -d
```

### 6. Generar la clave de aplicación

```bash
./vendor/bin/sail artisan key:generate
```

### 7. Crear las tablas y generar datos de prueba

```bash
./vendor/bin/sail artisan migrate --seed
```

El proyecto incluye seeders que generan un usuario de prueba y 20 estudiantes ficticios.

Para recrear la base de datos desde cero y volver a generar los datos:

```bash
./vendor/bin/sail artisan migrate:fresh --seed
```

**Importante:** `migrate:fresh` elimina todas las tablas y sus datos antes de volver a crearlas.

### 8. Detener los contenedores

```bash
./vendor/bin/sail down
```

## Estructura de la base de datos

La aplicación utiliza las siguientes tablas:

| Tabla | Descripción |
|---|---|
| `users` | Usuarios registrados en la API. |
| `academies` | Academias pertenecientes a los usuarios. |
| `courses` | Cursos asociados a cada academia. |
| `students` | Estudiantes registrados en el sistema. |
| `course_student` | Tabla intermedia que relaciona cursos y estudiantes. |

### Relaciones entre tablas

- **User - Academy:** relación uno a muchos (1:N). Un usuario puede tener varias academias.
- **Academy - Course:** relación uno a muchos (1:N). Una academia puede ofrecer varios cursos.
- **Course - Student:** relación muchos a muchos (N:N). Un curso puede tener varios estudiantes y un estudiante puede matricularse en varios cursos.

La tabla intermedia `course_student` almacena también la fecha de matriculación.

## Autenticación y autorización

La API utiliza **Laravel Sanctum** para gestionar la autenticación mediante tokens.

Los usuarios deben registrarse o iniciar sesión para obtener un token de acceso. Este token debe enviarse en las peticiones protegidas mediante la cabecera:

```http
Authorization: Bearer TOKEN
```

Las operaciones de modificación y eliminación de academias y cursos están sujetas a comprobaciones de propiedad y autorización.

## Endpoints de la API

Todas las rutas utilizan el prefijo `/api`.

### Autenticación

| Método | Endpoint | Descripción |
|---|---|---|
| POST | `/api/register` | Registrar un usuario. |
| POST | `/api/login` | Iniciar sesión y obtener un token. |
| POST | `/api/logout` | Cerrar sesión e invalidar el token actual. |

### Academias

Rutas protegidas mediante autenticación.

| Método | Endpoint | Descripción |
|---|---|---|
| GET | `/api/academies` | Listar academias. |
| POST | `/api/academies` | Crear una academia. |
| GET | `/api/academies/{academy}` | Consultar una academia. |
| PUT | `/api/academies/{academy}` | Actualizar una academia. |
| PATCH | `/api/academies/{academy}` | Actualizar parcialmente una academia. |
| DELETE | `/api/academies/{academy}` | Eliminar una academia. |

Cualquier usuario autenticado puede consultar las academias, pero únicamente su propietario puede modificarlas o eliminarlas.

### Cursos

Rutas protegidas mediante autenticación, salvo la consulta pública de cursos de una academia.

| Método | Endpoint | Descripción |
|---|---|---|
| GET | `/api/courses` | Listar cursos. |
| POST | `/api/courses` | Crear un curso. |
| GET | `/api/courses/{course}` | Consultar un curso. |
| PUT | `/api/courses/{course}` | Actualizar un curso. |
| PATCH | `/api/courses/{course}` | Actualizar parcialmente un curso. |
| DELETE | `/api/courses/{course}` | Eliminar un curso. |
| GET | `/api/academy/{academy}/courses` | Consultar los cursos de una academia. |

La creación, modificación y eliminación de cursos requiere ser propietario de la academia a la que pertenecen.

### Matrículas de estudiantes

Estas rutas requieren autenticación y autorización.

| Método | Endpoint | Descripción |
|---|---|---|
| GET | `/api/courses/{course}/students` | Consultar los estudiantes matriculados en un curso. |
| POST | `/api/courses/{course}/enroll/{student}` | Matricular un estudiante en un curso. |
| DELETE | `/api/courses/{course}/unenroll/{student}` | Desmatricular un estudiante de un curso. |

El sistema controla que no se produzcan matrículas duplicadas y que no se supere la capacidad máxima del curso.

## Códigos de respuesta HTTP

La API utiliza los siguientes códigos de estado:

| Código | Significado |
|---|---|
| 200 | Petición realizada correctamente. |
| 201 | Recurso creado correctamente. |
| 204 | Recurso eliminado correctamente. |
| 401 | Usuario no autenticado o token inválido. |
| 403 | Usuario autenticado sin permisos suficientes. |
| 404 | Recurso no encontrado. |
| 409 | Conflicto, como una matrícula duplicada o un curso completo. |
| 422 | Error de validación de los datos enviados. |

## Pruebas con Postman

La API se ha probado mediante Postman, verificando los endpoints, la autenticación, los permisos de acceso, las relaciones entre modelos y las validaciones.

Para realizar las pruebas es necesario iniciar sesión y utilizar el token de autenticación en las peticiones protegidas.

## Autor

Proyecto desarrollado como parte del ciclo formativo de **Desarrollo de Aplicaciones Web (DAW)**.