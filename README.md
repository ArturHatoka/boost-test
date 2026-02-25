# BoostTest

Test task project: "Client List" application.

## Stack

- Frontend: Vue 3 (planned)
- Backend: Yii2 Basic (`back/`)

## Project structure

```text
BoostTest/
  back/        Yii2 backend
  TODO.txt     Original task description
```

## Requirements

- PHP 8.3+ (CLI)
- Composer 2+
- Node.js 20+ and npm/yarn (for upcoming frontend part)

## Backend setup

```bash
cd back
composer install
# create MySQL database "yii2basic" and update back/config/db.php if needed
php yii migrate/up --interactive=0
php yii serve --port=8080 --docroot=web
```

Backend will be available at:

```text
http://localhost:8080
```

## API base

API module is configured under:

```text
/api/client
```

Implemented endpoints:

- `GET /api/client`
- `POST /api/client`
- `GET /api/client/{id}`
- `PUT /api/client/{id}`
- `DELETE /api/client/{id}`

## Backend architecture

Client API is decomposed into layers:

- `back/modules/api/controllers` - HTTP layer (`ApiController`, `ClientController`)
- `back/modules/api/requests` - input models and validation rules
- `back/modules/api/services` - application/business orchestration
- `back/modules/api/repositories` - persistence contracts and ActiveRecord implementation
- `back/modules/api/dto` - transport objects between layers
- `back/modules/api/transformers` - response shaping
- `back/modules/api/exceptions` - domain-level exceptions

Request payload for create/update:

```json
{
  "name": "Acme Corp",
  "state": "California",
  "status": "Active"
}
```

`status` accepts: `Active`, `Inactive`.

## Data model

`Client` fields:

- `id`
- `name` (required)
- `state` (required)
- `status` (`Active` by default)

Migration file:

- `back/migrations/m260225_094500_create_client_table.php`

## DB config

Default backend DB config uses MySQL:

- host: `localhost`
- database: `yii2basic`
- user: `root`

Connection config:

- `back/config/db.php`

Edit `dsn/username/password` there for your environment.

## Notes

- This repository is being built step-by-step according to `TODO.txt`.
- At this stage, backend CRUD API for `Client` is implemented.
