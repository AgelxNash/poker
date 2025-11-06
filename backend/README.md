# Backend (Slim 4 bootstrap)

Минимальный HTTP API для Poker Planning. Временно хранит данные в `var/data/sessions.json` (JSON-файл).

## Требования
- PHP 8.2+
- Composer 2.x

## Установка
```bash
cd backend
composer install
php -S 0.0.0.0:8080 -t public
# или
php public/index.php
```

## Эндпоинты (черновик)
- `GET  /api/health` → `{ "status": "ok" }`
- `POST /api/sessions` → создать сессию. Body: `{ "room": "A1", "deck": "fibonacci" }`
- `GET  /api/sessions/{id}` → получить сессию
- `POST /api/sessions/{id}/vote` → проголосовать. Body: `{ "user": "eugene", "value": "5" }`
- `POST /api/sessions/{id}/finalize` → финализировать (медиана)
