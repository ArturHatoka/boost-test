# Backend (`back`)

Yii2 Basic backend for BoostTest.

## Requirements

- PHP 8.3+
- Composer 2+
- MySQL (default config uses database `yii2basic`)

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

## Module layout

- `modules/api/controllers` - HTTP layer
- `modules/api/requests` - request validation
- `modules/api/services` - application logic
- `modules/api/repositories` - persistence
- `modules/api/dto` - transport objects
- `modules/api/transformers` - response shaping
- `modules/api/exceptions` - domain exceptions
