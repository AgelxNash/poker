#!/usr/bin/env bash
set -euo pipefail
cd "$(dirname "$0")/.."
docker compose -f deploy/docker-compose.dev.yml up -d --remove-orphans
echo "Up: http://localhost:8000 (edge) | Vite: http://localhost:5173 | WS: ws://localhost:8000/ws"
