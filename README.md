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
- inline status switch (Active/Inactive) without page reload

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

## Бизнес-задача

Нам нужно отслеживать клиентов, у которых давно не было активности.

### Какие поля добавить в модель

В `Client`/таблицу `client`:

- `last_activity_at` (`DATETIME`, nullable) — дата и время последней активности клиента.
- `activity_count` (`INT`, default `0`) — количество зафиксированных активностей.
- `activity_status` (`VARCHAR`, default `Normal`) — вычисляемый бизнес-статус (`Normal`, `InactiveByActivity`).

Опционально (для прозрачности аудита):

- отдельную таблицу `client_activity` (`id`, `client_id`, `type`, `created_at`, `meta`) для истории событий.

### Как определять неактивных клиентов

Правило:

- клиент считается неактивным по активности, если `last_activity_at` пустой **или** `last_activity_at < now() - N дней`.

Где `N` — настраиваемый порог (например, `30` дней) через конфиг приложения.

При любом событии активности:

- обновляем `last_activity_at` на текущее время,
- увеличиваем `activity_count`,
- пересчитываем `activity_status`.

### Как отображать в интерфейсе

В таблице клиентов:

- колонка `Last activity` (дата последней активности),
- визуальный бейдж `Inactive by activity` для неактивных.

В фильтрах:

- `All / Only inactive by activity`,
- фильтр по диапазону дней неактивности.

Дополнительно:

- сортировка по давности активности (самые “старые” сверху),
- быстрый action “показать только проблемных” для менеджера.

## Продакшн-доработки


1. **Пункт:** Авторизация и роли (RBAC).  
   **Что даст:** Контроль доступа к данным и действиям пользователей, снижение риска несанкционированных изменений.  
   **Почему:** Клиентские данные и CRUD-операции требуют разграничения прав (`admin`, `manager`, `viewer`).

2. **Пункт:** Тесты + CI.  
   **Что даст:** Раннее выявление ошибок, стабильные поставки и меньше регрессий после изменений.  
   **Почему:** Автопроверки (`lint`, `test`, `build`) должны блокировать merge при падении качества.

3. **Пункт:** Аудит, логирование и мониторинг.  
   **Что даст:** Быстрая диагностика инцидентов и прозрачность изменений по клиентам.  
   **Почему:** В продакшне важно видеть, кто/что/когда изменил, и контролировать ошибки, latency и доступность.

### Дополнительно можно добавить

4. **Пункт:** Версионирование API (`/api/v1`, `/api/v2`).  
   **Что даст:** Безболезненные изменения API без поломки старых интеграций.  
   **Почему:** Потребители API обновляются не одновременно с сервером.

5. **Пункт:** Резервные копии и план восстановления (DR).  
   **Что даст:** Снижение потерь данных и предсказуемое восстановление после сбоев.  
   **Почему:** Для рабочей системы нужен проверяемый сценарий восстановления.

6. **Пункт:** Rate limiting и защита от злоупотреблений.  
   **Что даст:** Устойчивость API к всплескам нагрузки и базовая защита от брутфорса.  
   **Почему:** Endpoint'ы без ограничений быстро становятся точкой отказа.

7. **Пункт:** Серверная пагинация, фильтрация и сортировка.  
   **Что даст:** Быструю работу списка клиентов на больших объёмах данных.  
   **Почему:** Перенос обработки на backend снижает нагрузку на браузер и сеть.

8. **Пункт:** Soft delete + восстановление записей.  
   **Что даст:** Защиту от случайных удалений и возможность отката.  
   **Почему:** В реальных процессах человеческие ошибки неизбежны, данные должны быть восстановимы.

9. **Пункт:** OpenAPI/Swagger контракт.  
   **Что даст:** Единое и актуальное описание API для frontend и внешних интеграций.  
   **Почему:** Контракт снижает расхождения между реализацией и ожиданиями клиентов API.

10. **Пункт:** Кэширование и индексы БД.  
    **Что даст:** Ускорение чтения и стабильную производительность под нагрузкой.  
    **Почему:** С ростом данных узкие места чаще возникают в БД и повторяющихся запросах.

11. **Пункт:** Усиление security baseline (`CORS`, security headers, strict validation).  
    **Что даст:** Меньше уязвимостей и безопаснее обработка пользовательского ввода.  
    **Почему:** Публичные API и веб-интерфейсы требуют базового набора защит по умолчанию.

12. **Пункт:** Docker + staging/prod окружения.  
    **Что даст:** Воспроизводимые деплои и предсказуемую эксплуатацию.  
    **Почему:** Одинаковая инфраструктура между средами снижает риск сюрпризов на проде.
