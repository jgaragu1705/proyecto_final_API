# API REST - Gestión de Academias

Proyecto final del módulo de Desarrollo Web en Entorno Servidor (DAW).

API REST desarrollada con Laravel para gestionar academias, cursos y estudiantes mediante peticiones HTTP y respuestas JSON.

## Tecnologías utilizadas

- PHP
- Laravel 13
- MySQL
- Laravel Sanctum
- Docker y Laravel Sail

## Instalación y ejecución

### 1. Clonar el repositorio

```bash
git clone https://github.com/jgaragu1705/proyecto_final_API.git
cd proyecto_final_API
```

### 2. Configurar el entorno

Crear el archivo `.env` a partir del archivo de ejemplo:

```bash
cp .env.example .env
```

### 3. Iniciar los contenedores

```bash
./vendor/bin/sail up -d
```

### 4. Ejecutar las migraciones

```bash
./vendor/bin/sail artisan migrate
```

### 5. Generar datos de prueba (opcional)

El proyecto incluye un seeder que genera 20 estudiantes ficticios y un usuario de prueba.

Para ejecutar los seeders:

```bash
./vendor/bin/sail artisan db:seed
```

También se pueden crear las tablas desde cero y generar los datos de prueba con:

```bash
./vendor/bin/sail artisan migrate:fresh --seed
```

**Importante:** `migrate:fresh` elimina todas las tablas y sus datos antes de volver a crearlas.

## Estructura de la base de datos

El proyecto cuenta con las siguientes tablas:

- `users`: usuarios de la API.
- `academies`: academias registradas.
- `courses`: cursos pertenecientes a las academias.
- `students`: estudiantes.
- `course_student`: tabla intermedia para las matrículas de estudiantes en cursos.

## Autenticación

La API utiliza Laravel Sanctum para gestionar la autenticación mediante tokens.

## Autor

Proyecto desarrollado como parte del ciclo formativo de Desarrollo de Aplicaciones Web (DAW).