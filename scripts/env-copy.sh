#!/usr/bin/env bash
set -euo pipefail
cd "$(dirname "$0")/.."

copy_env() {
  local dir="$1"
  if [[ -f "$dir/.env" ]]; then
    echo "$dir/.env already exists"
  elif [[ -f "$dir/.env.example" ]]; then
    cp "$dir/.env.example" "$dir/.env"
    echo "copied $dir/.env.example -> $dir/.env"
  else
    echo "skip $dir (no .env.example)"
  fi
}

copy_env backend || true
copy_env ws || true
copy_env frontend || true
