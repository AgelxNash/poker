# Runbook: Dev Docker Stack (backend + ws + frontend + nginx)

Этот сценарий не трогает существующий `Makefile`/Compose из репозитория — добавлены отдельные dev-файлы.
Edge-узел Nginx слушает **http://localhost:8000** и проксирует:
- `/` → фронтенд (Vite dev, порт 5173)
- `/api/*` → backend (PHP Slim, порт 8080)
- `/ws` (WS) → ws-сервер (порт 7071)

## Быстрый старт
```bash
# единый make up (через отдельный Makefile.dev)
make -f Makefile.dev up

# альтернативно напрямую
bash scripts/dev-up.sh

# остановить
make -f Makefile.dev down
```

## Порты/URL
- Edge: http://localhost:8000
- Frontend (Vite): http://localhost:5173
- API напрямую: http://localhost:8080/api/health
- WS напрямую: ws://localhost:7071

## Заметки
- Для фронтенда заданы env:
  - `VITE_API_URL=http://localhost:8000`
  - `VITE_WS_URL=ws://localhost:8000/ws`
- Файлы добавлены с учётом структуры репозитория (`deploy/`, `scripts/`) и README, где указан `make up` и порты 8000/5173.
