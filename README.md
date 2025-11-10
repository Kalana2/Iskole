# Iskole

Iskole is a small MVC-style PHP web application for basic school management (announcements, attendance, timetables, marks, user roles such as admin, teacher, MP, parent and student). The project is designed to run in a Docker development environment and uses a simple homegrown MVC bootstrap (no framework).

## Features

- MVC structure (controllers, models, views)
- Role-based views (admin, teacher, student, parent, mp)
- Simple session handling and routing via `public/index.php`
- MySQL database connection with retry logic (useful when running with Docker Compose)
- Development-ready Dockerfile + `docker-compose.yml`

## Repository layout (important folders)

- `app/` - application source (Controllers, Models, Views, Core classes)
  - `Core/` - core bootstrap classes: `App.php`, `Controller.php`, `Database.php`, `Session.php`
  - `Controllers/` - controller classes (e.g. `LoginController.php`, `AdminController.php`)
  - `Model/` - model classes
  - `Views/` - view templates
- `public/` - public document root served by Apache (contains `index.php`, helper scripts and assets)
- `docker/` - development PHP config (`php.ini`)
- `Dockerfile`, `docker-compose.yml` - Docker build and orchestration

## Requirements

- Docker
- Docker Compose

(You can run the app without Docker, but Docker is the supported setup in this repository.)

## Environment variables

The app reads database connection variables from the environment. Create a `.env` file in the project root (used by `docker-compose`) with values similar to:

```
MYSQL_HOST=db
MYSQL_USER=root
MYSQL_PASSWORD=root
MYSQL_DB=iskole
MYSQL_PORT=3306
```

Defaults are also present in `app/Core/Database.php` (host `db`, port `3306`, database `iskole`, user `root`, password `root`) to make development simple when using the included `docker-compose.yml`.

## Running (development)

1. Build and start services:

   docker-compose up --build -d

2. Open the app in your browser:

   - App: http://localhost:8083/
   - phpMyAdmin: http://localhost:8084/ (login with root / root)

3. Helpful checks

- DB health / connectivity check: `public/db_test.php` (temporary helper) — open in browser or curl: http://localhost:8083/db_test.php
- Install check: `public/install.php` prints a simple DB check message

## Notes on routing and auth

- The front controller is `public/index.php` which initializes the app and starts the session.
- `app/Core/App.php` parses `$_GET['url']` and dispatches to controllers in `app/Controllers/`.
- Public access is currently restricted to the `LoginController`. Other controllers require a logged-in session (`$_SESSION['user_id']`).

## Database

There is no provided schema in this repo — the project expects an existing database schema or manual setup. The `Database` class will attempt to connect and includes a retry/backoff loop to wait for the DB to become available (useful in Docker Compose environments).

## Development tips

- Files are mounted into the container (see `docker-compose.yml`), so changes on the host are reflected immediately during development.
- Apache `mod_rewrite` is enabled in the Dockerfile to support clean URLs.

## Contributing

Open an issue or submit a pull request. Keep changes small and focused, and include brief test instructions.

## License

This repository does not include an explicit license file. Add a `LICENSE` if you want to publish or share the project under a specific license.
