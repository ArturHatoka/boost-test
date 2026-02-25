# Backend (`back`)

Yii2 Basic backend for BoostTest.

## Requirements

- PHP 8.3+
- Composer 2+
- PDO driver for selected database:
  - SQLite: `pdo_sqlite`
  - MySQL: `pdo_mysql`
  - PostgreSQL: `pdo_pgsql`

## Database (Default: SQLite)

Current default configuration:

- `DB_DRIVER=sqlite`
- `DB_DATABASE=runtime/app.db`

`back/config/db.php` supports these drivers:

- `sqlite` (default)
- `mysql`
- `pgsql`

`back/.env` is auto-loaded by backend config.
OS-level environment variables override values from `.env`.
Use `back/.env.example` as a reference for variables.

## Setup

```bash
composer install
php yii migrate/up --interactive=0
php yii serve --port=8080 --docroot=web
```

Backend URL:

```text
http://localhost:8080
```

## How to Switch DB

### MySQL

PowerShell:

```powershell
$env:DB_DRIVER = "mysql"
$env:DB_HOST = "127.0.0.1"
$env:DB_PORT = "3306"
$env:DB_DATABASE = "yii2basic"
$env:DB_USERNAME = "root"
$env:DB_PASSWORD = ""
php yii migrate/up --interactive=0
```

Bash:

```bash
export DB_DRIVER=mysql
export DB_HOST=127.0.0.1
export DB_PORT=3306
export DB_DATABASE=yii2basic
export DB_USERNAME=root
export DB_PASSWORD=
php yii migrate/up --interactive=0
```

### PostgreSQL

PowerShell:

```powershell
$env:DB_DRIVER = "pgsql"
$env:DB_HOST = "127.0.0.1"
$env:DB_PORT = "5432"
$env:DB_DATABASE = "yii2basic"
$env:DB_USERNAME = "postgres"
$env:DB_PASSWORD = "postgres"
php yii migrate/up --interactive=0
```

Bash:

```bash
export DB_DRIVER=pgsql
export DB_HOST=127.0.0.1
export DB_PORT=5432
export DB_DATABASE=yii2basic
export DB_USERNAME=postgres
export DB_PASSWORD=postgres
php yii migrate/up --interactive=0
```

## Tests DB

`back/config/test_db.php` supports `TEST_DB_*` overrides.

Examples:

- `TEST_DB_DRIVER=sqlite`
- `TEST_DB_DATABASE=runtime/test.db`

## API

Base prefix:

```text
/api/client
```

Endpoints:

- `GET /api/client`
- `POST /api/client`
- `GET /api/client/{id}`
- `PUT /api/client/{id}`
- `DELETE /api/client/{id}`

Payload for create/update:

```json
{
  "name": "Acme Corp",
  "state": "California",
  "status": "Active"
}
```

## Module Layout

- `modules/api/controllers` - HTTP layer
- `modules/api/requests` - request validation
- `modules/api/services` - application logic
- `modules/api/repositories` - persistence
- `modules/api/dto` - transport objects
- `modules/api/transformers` - response shaping
- `modules/api/exceptions` - domain exceptions
