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

Current endpoint (bootstrap check):

- `GET /api/client`

## Notes

- This repository is being built step-by-step according to `TODO.txt`.
- At this stage, backend skeleton and API routing are initialized.

