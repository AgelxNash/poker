
# Planning Poker — self-hosted (Laravel + Vue + WebSockets)

Готовая основа проекта с Docker Compose и bootstrap-скриптами. На первом запуске бэкенд и фронтенд будут сгенерированы (Laravel 11, Vue 3), установлены зависимости и влит наш доменный код (миграции, контроллеры, сервисы, компоненты).

## Быстрый старт
1. Установите Docker и Docker Compose.
2. Распакуйте архив и перейдите в папку.
3. Выполните:
   ```bash
   make up
   ```
4. После сборки:
   - Backend API: http://localhost:8000
   - Frontend Dev (Vite): http://localhost:5173
