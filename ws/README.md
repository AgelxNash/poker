# WS сервер (Node.js)

Минимальный WebSocket-сервер для Poker Planning. Транслирует события внутри `sessionId`.

## Запуск
```bash
cd ws
npm i
npm run dev   # или npm start
# PORT=7071 по умолчанию
```

## Протокол (JSON)
- `join`      — `{"type":"join","sessionId":"...","user":"eugene"}`
- `vote`      — `{"type":"vote","sessionId":"...","user":"eugene","value":"5"}`
- `finalize`  — `{"type":"finalize","sessionId":"..."}`
Ответы бродкастятся всем участникам той же `sessionId`.
