.PHONY: up down test pint stan keycloak-realm-reset

up:
	docker compose up --build -d

down:
	docker compose down

test:
	php artisan test --compact

pint:
	vendor/bin/pint --dirty --format agent

stan:
	vendor/bin/phpstan analyse --no-progress --memory-limit=1G

keycloak-realm-reset:
	docker compose down keycloak keycloak-db
	docker volume rm seatguard_keycloakdbdata
	docker compose up -d keycloak
