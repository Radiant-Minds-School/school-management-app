# Variables
SAIL = ./vendor/bin/sail
APP_PORT = 80
APP_URL = http://localhost:$(APP_PORT)
VITE_PORT = 5173
VITE_URL = http://localhost:$(VITE_PORT)

.PHONY: help install up down restart build shell test npm composer artisan migrate seed fresh status logs ps

# Default target
## Show this help message
help:
	@echo "Usage: make [target]"
	@echo ""
	@echo "Targets:"
	@awk '/^[a-zA-Z0-9_-]+:/ { \
		helpMessage = match(lastLine, /^## (.*)/); \
		if (helpMessage) { \
			helpCommand = substr($$1, 0, index($$1, ":")); \
			helpMessage = substr(lastLine, RSTART + 3, RLENGTH); \
			printf "  %-20s %s\n", helpCommand, helpMessage; \
		} \
	} \
	{ lastLine = $$0 }' $(MAKEFILE_LIST)

## Install dependencies and set up the project
install:
	@echo "Installing dependencies..."
	composer install
	cp .env.example .env
	$(SAIL) up -d
	$(SAIL) artisan key:generate
	$(SAIL) npm install
	@echo "Installation complete!"

## Start the development environment
up:
	$(SAIL) up -d

## Stop the development environment
down:
	$(SAIL) down

## Restart the development environment
restart:
	$(SAIL) down
	$(SAIL) up -d

## Rebuild containers
build:
	$(SAIL) build --no-cache

## Open shell in the app container
shell:
	$(SAIL) shell

## Run tests
test:
	$(SAIL) test

## Run npm commands (usage: make npm cmd=install)
npm:
	$(SAIL) npm $(cmd)

## Run composer commands (usage: make composer cmd=require pkg=laravel/sanctum)
composer:
ifdef pkg
	$(SAIL) composer $(cmd) $(pkg)
else
	$(SAIL) composer $(cmd)
endif

## Run artisan commands (usage: make artisan cmd=migrate)
artisan:
	$(SAIL) artisan $(cmd)

## Run database migrations
migrate:
	$(SAIL) artisan migrate

## Run database seeder
seed:
	$(SAIL) artisan db:seed

## Fresh database migration with seeding
fresh:
	$(SAIL) artisan migrate:fresh --seed

## Show container status
status:
	$(SAIL) status

## View container logs
logs:
	$(SAIL) logs

## Show running containers
ps:
	docker ps

# Show application URLs
urls:
	@echo "Application URLs:"
	@echo "  Main URL:     $(APP_URL)"
	@echo "  Vite Dev:     $(VITE_URL)"
	@echo "  Mailpit:      http://localhost:8025"
	@echo "  Adminer:      http://localhost:8080"
