#!/usr/bin/env bash
set -euo pipefail

cd /frontend

if [ ! -f "package.json" ]; then
  pnpm create vite@latest . --template vue
fi

echo "[bootstrap] Install deps"
pnpm i vue-router pinia axios
# realtime
pnpm i laravel-echo pusher-js

echo "[bootstrap] Copy blueprint"
rsync -a /app/frontend-blueprint/ /frontend/
