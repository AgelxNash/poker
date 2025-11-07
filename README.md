
# Poker Planning — Zero Branch

**Purpose:** стартовая ветка (нулевая) c минимальным runnable-шаблоном monorepo: backend (PHP), frontend (Vue 3 + Vite), CI GitHub Actions.

- Backend: чистый PHP (PSR-4), роутинг на FastRoute, HTTP-обертка на laminas-diactoros, простые контроллеры и in-memory storage. Тесты — PHPUnit. Линтер — Laravel Pint. Анализ — PHPStan.
- Frontend: Vue 3 + Vite + Pinia, базовые страницы Lobby/Room и мок вебсокета. Линтер — ESLint.
- CI: единый workflow `.github/workflows/ci.yml`: composer install, phpunit + phpstan + pint, node build + eslint, архивирование артефактов.
- Docker: nginx + php-fpm + node для локальной разработки.

Дальше можно мигрировать backend на Laravel без изменения публичного API (см. `backend/public/index.php` и `backend/src/Controllers`).

## Quick start (local)

```bash
# Backend
cd backend
composer install
php -S 127.0.0.1:8080 -t public

# Frontend
cd ../frontend
npm ci
npm run dev
```

Backend: http://127.0.0.1:8080/api/health  
Frontend dev: http://127.0.0.1:5173

## Run tests

```bash
cd backend
composer test
```

## Docker (optional)

```bash
docker compose up --build
```

## Structure

```
backend/   PHP (FastRoute + Diactoros), PHPUnit, PHPStan, Pint
frontend/  Vue 3 + Vite + Pinia, ESLint
.github/workflows/ci.yml
docker-compose.yml
```

