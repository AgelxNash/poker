
#!/usr/bin/env sh
set -e
cd /frontend

if [ ! -f "package.json" ]; then
  echo "[bootstrap] Creating Vite Vue app..."
  pnpm create vite@latest . --template vue
fi

echo "[bootstrap] Install deps"
pnpm i vue-router pinia axios

echo "[bootstrap] Copy blueprint"
rsync -a /app/frontend-blueprint/ /frontend/

echo "[bootstrap] Install Tailwind"
pnpm i -D tailwindcss postcss autoprefixer
npx tailwindcss init -p || true

# Tailwind config for Vue + Vite
cat > tailwind.config.js <<'EOF'
/** @type {import('tailwindcss').Config} */
export default {
  content: ["./index.html", "./src/**/*.{vue,js,ts}"],
  theme: { extend: {} },
  plugins: [],
}
EOF

echo "[bootstrap] Dev server tip: pnpm run dev -- --host"
