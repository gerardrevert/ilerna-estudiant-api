install:
	cd laravel && cp -n .env.example .env
	cd laravel && docker compose up -d --build
	cd laravel && docker compose exec -T app composer install
	cd laravel && docker compose exec -T app php artisan key:generate
	cd laravel && docker compose exec -T app php artisan migrate --seed
	@echo "API disponible a: http://localhost:8083"
	@echo "phpMyAdmin disponible a: http://localhost:8082"

test:
	cd laravel && docker compose exec -T app php artisan test

down:
	cd laravel && docker compose down
