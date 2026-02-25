# BoostTest

Тестовое приложение "Client List".

## Stack

- Frontend: Vue 3 + Element Plus + axios (`front/`)
- Backend: Yii2 Basic (`back/`)
- Default DB: SQLite (`back/runtime/app.db`)

## Project Structure

```text
BoostTest/
  front/       Vue 3 frontend
  back/        Yii2 backend
  TODO.txt     Original task description
```

## Requirements

- PHP 8.3+
- Composer 2+
- Node.js 20+ and npm
- PDO driver for selected database:
  - SQLite: `pdo_sqlite`
  - MySQL: `pdo_mysql`
  - PostgreSQL: `pdo_pgsql`

## Quick Start

### Backend (SQLite by default)

```bash
cd back
composer install
php yii migrate/up --interactive=0
php yii serve --port=8080 --docroot=web
```

Backend URL:

```text
http://localhost:8080
```

### Frontend

```bash
cd front
npm install
npm run dev
```

Frontend URL:

```text
http://localhost:5173
```

## Database Configuration

`back/config/db.php` now supports `sqlite`, `mysql`, `pgsql` through environment variables.
Backend config auto-loads `back/.env` (if present).
OS-level environment variables have priority over `.env`.

Default values:

- `DB_DRIVER=sqlite`
- `DB_DATABASE=runtime/app.db`

Reference file:

- `back/.env.example`

### Switch to MySQL

PowerShell:

```powershell
$env:DB_DRIVER = "mysql"
$env:DB_HOST = "127.0.0.1"
$env:DB_PORT = "3306"
$env:DB_DATABASE = "yii2basic"
$env:DB_USERNAME = "root"
$env:DB_PASSWORD = ""
php yii migrate/up --interactive=0
php yii serve --port=8080 --docroot=web
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
php yii serve --port=8080 --docroot=web
```

### Switch to PostgreSQL

PowerShell:

```powershell
$env:DB_DRIVER = "pgsql"
$env:DB_HOST = "127.0.0.1"
$env:DB_PORT = "5432"
$env:DB_DATABASE = "yii2basic"
$env:DB_USERNAME = "postgres"
$env:DB_PASSWORD = "postgres"
php yii migrate/up --interactive=0
php yii serve --port=8080 --docroot=web
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
php yii serve --port=8080 --docroot=web
```

### Test DB Overrides

`back/config/test_db.php` supports `TEST_DB_*` variables.

Examples:

- `TEST_DB_DRIVER=sqlite`
- `TEST_DB_DATABASE=runtime/test.db`

## Frontend Environment

Use `front/.env.example`:

- `VITE_API_BASE_URL` (default: `/api`)
- `VITE_DEV_PROXY_TARGET` (default: `http://localhost:8080`)

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

`status` accepts: `Active`, `Inactive`.

## Backend Architecture

- `back/modules/api/controllers` - HTTP layer (`ApiController`, `ClientController`)
- `back/modules/api/requests` - input models and validation rules
- `back/modules/api/services` - application/business orchestration
- `back/modules/api/repositories` - persistence contracts and ActiveRecord implementation
- `back/modules/api/dto` - transport objects between layers
- `back/modules/api/transformers` - response shaping
- `back/modules/api/exceptions` - domain-level exceptions

## Business Task

Нужно отслеживать клиентов, у которых давно не было активности.

Предложение:

- Добавить в модель поля `last_activity_at`, `activity_count`, `activity_status`.
- Считать клиента неактивным, если `last_activity_at` пустой или старше порога `N` дней.
- В UI показать колонку последней активности, бейдж "Inactive by activity" и фильтр неактивных.

## Production Improvements

1. **Auth + RBAC** - добавить аутентификацию и роли (`admin/manager/viewer`), чтобы защитить данные клиентов и ограничить чувствительные действия.
2. **Тесты + CI** - запускать `lint/test/build` на каждый коммит, чтобы раньше ловить регрессии и стабилизировать релизы.
3. **Аудит / логирование / мониторинг** - фиксировать изменения клиентов и метрики API, чтобы быстрее расследовать инциденты.
4. **Версионирование API** - использовать `/api/v1`, `/api/v2`, чтобы развивать API без поломки существующих интеграций.
5. **Резервные копии + DR-план** - определить политику бэкапов и восстановления для снижения потерь данных и времени простоя.
6. **Rate limiting** - ограничить частоту запросов по пользователю/клиенту для защиты API от перегрузки и злоупотреблений.
7. **Серверная пагинация/фильтрация/сортировка** - перенести тяжелую обработку списка на backend для лучшей производительности на больших данных.
8. **Soft delete + восстановление** - заменить жёсткое удаление на восстанавливаемое, чтобы избежать безвозвратной потери данных.
9. **OpenAPI/Swagger** - опубликовать контракт API для фронтенда и интеграций, чтобы синхронизировать форматы запросов и ответов.
10. **Индексы БД + кэширование** - ускорить медленные запросы и чтение часто запрашиваемых данных под нагрузкой.
11. **Базовая безопасность** (`CORS`, заголовки, strict validation) - включить безопасные настройки по умолчанию и строгую валидацию входных данных.
12. **Docker + staging/prod окружения** - унифицировать окружение и процесс деплоя для предсказуемых релизов.

## Notes

- This repository is being built step-by-step according to `TODO.txt`.
- At this stage, backend CRUD API and frontend CRUD UI for `Client` are implemented.
