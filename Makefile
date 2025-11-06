
.PHONY: up down logs sh-app migrate seed

up:
	docker compose -f deploy/docker-compose.yml up -d --build
	@sleep 5
	docker compose -f deploy/docker-compose.yml exec app bash -lc "./scripts/bootstrap-backend.sh || true"
	docker compose -f deploy/docker-compose.yml exec frontend sh -lc "./scripts/bootstrap-frontend.sh || true"
	@echo "Done. Backend http://localhost:8000  Frontend http://localhost:5173"

down:
	docker compose -f deploy/docker-compose.yml down -v

logs:
	docker compose -f deploy/docker-compose.yml logs -f --tail=200

sh-app:
	docker compose -f deploy/docker-compose.yml exec app bash

migrate:
	docker compose -f deploy/docker-compose.yml exec app php artisan migrate

seed:
	docker compose -f deploy/docker-compose.yml exec app php artisan db:seed
