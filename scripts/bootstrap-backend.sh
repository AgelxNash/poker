
#!/usr/bin/env bash
set -euo pipefail
cd /app

if [ ! -d "backend" ]; then
  echo "[bootstrap] Creating Laravel project..."
  composer create-project laravel/laravel backend
fi

cd backend

if [ ! -f ".env" ]; then
  cp .env.example .env || true
  php artisan key:generate || true
fi

echo "[bootstrap] Require backend packages"
composer require laravel/sanctum guzzlehttp/guzzle barryvdh/laravel-dompdf
composer require beyondcode/laravel-websockets

echo "[bootstrap] Publish packages"
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider" --force || true
php artisan vendor:publish --provider="BeyondCode\LaravelWebSockets\WebSocketsServiceProvider" --force || true

echo "[bootstrap] Tune .env"
sed -i "s/^BROADCAST_DRIVER=.*/BROADCAST_DRIVER=pusher/" .env || true
sed -i "s/^CACHE_DRIVER=.*/CACHE_DRIVER=redis/" .env || true
sed -i "s/^QUEUE_CONNECTION=.*/QUEUE_CONNECTION=redis/" .env || true
sed -i "s/^SESSION_DRIVER=.*/SESSION_DRIVER=redis/" .env || true
sed -i "s/^DB_HOST=.*/DB_HOST=db/" .env || true
sed -i "s/^DB_DATABASE=.*/DB_DATABASE=poker/" .env || true
sed -i "s/^DB_USERNAME=.*/DB_USERNAME=poker/" .env || true
sed -i "s/^DB_PASSWORD=.*/DB_PASSWORD=poker/" .env || true
grep -q "^PUSHER_APP_ID=" .env || echo "PUSHER_APP_ID=local" >> .env
grep -q "^PUSHER_APP_KEY=" .env || echo "PUSHER_APP_KEY=local" >> .env
grep -q "^PUSHER_APP_SECRET=" .env || echo "PUSHER_APP_SECRET=local" >> .env
grep -q "^PUSHER_HOST=" .env || echo "PUSHER_HOST=websockets" >> .env
grep -q "^PUSHER_PORT=" .env || echo "PUSHER_PORT=6001" >> .env
grep -q "^PUSHER_SCHEME=" .env || echo "PUSHER_SCHEME=http" >> .env

echo "[bootstrap] Copy blueprint files"
rsync -a --exclude ".DS_Store" /app/backend-blueprint/ /app/backend/

echo "[bootstrap] Migrate & seed"
php artisan migrate --force || true
php artisan db:seed --force || true

echo "[bootstrap] Backend bootstrap complete."
