# BoostTest

Test task project: "Client List" application.

## Stack

- Frontend: Vue 3 + Element Plus + axios (`front/`)
- Backend: Yii2 Basic (`back/`)

## Project structure

```text
BoostTest/
  front/       Vue 3 frontend
  back/        Yii2 backend
  TODO.txt     Original task description
```

## Requirements

- PHP 8.3+ (CLI)
- Composer 2+
- Node.js 20+ and npm/yarn

## Frontend setup

```bash
cd front
npm install
npm run dev
```

Frontend will be available at:

```text
http://localhost:5173
```

Frontend environment variables (see `front/.env.example`):

- `VITE_API_BASE_URL` (default: `/api`)
- `VITE_DEV_PROXY_TARGET` (default: `http://localhost:8080`)

The HTTP layer is built with axios:

- `front/src/shared/api/http.ts`
- `front/src/entities/client/api/clientApi.ts`

Development proxy forwards `/api/*` from Vite to backend.

Implemented UI in frontend:

- client table/list view
- create client form
- edit client form
- delete client action

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
- At this stage, backend CRUD API and frontend CRUD UI for `Client` are implemented.
