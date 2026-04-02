.PHONY: help up down build start stop restart logs logs-app logs-db shell shell-db clean install status ps

# Default target
help: ## Show this help message
	@echo "Available commands:"
	@echo ""
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-15s\033[0m %s\n", $$1, $$2}'

up: ## Start all containers in detached mode
	docker-compose up -d

build: ## Build and start all containers
	docker-compose up -d --build

start: ## Start existing containers
	docker-compose start

stop: ## Stop running containers
	docker-compose stop

down: ## Stop and remove containers, networks
	docker-compose down

restart: ## Restart all containers
	docker-compose restart

restart-app: ## Restart only the web application container
	docker-compose restart web

logs: ## Show logs from all containers
	docker-compose logs -f

logs-app: ## Show logs from web application
	docker-compose logs -f web

logs-db: ## Show logs from database
	docker-compose logs -f db

shell: ## Open shell in web application container
	docker-compose exec web bash

shell-db: ## Open MySQL shell in database container
	docker-compose exec db mysql -uroot -proot iskole

ps: ## List running containers
	docker-compose ps

status: ## Show status of all containers
	@docker-compose ps

clean: ## Remove containers, volumes, and images
	docker-compose down -v
	docker-compose down --rmi all

install: ## Install composer dependencies
	docker-compose exec web composer install

composer-update: ## Update composer dependencies
	docker-compose exec web composer update

db-backup: ## Backup database to file
	docker-compose exec db mysqldump -uroot -proot iskole > backup_$$(date +%Y%m%d_%H%M%S).sql

db-restore: ## Restore database from backup file (usage: make db-restore FILE=backup.sql)
	docker-compose exec -T db mysql -uroot -proot iskole < $(FILE)

phpmyadmin: ## Open phpMyAdmin in browser
	@echo "Opening phpMyAdmin at http://localhost:8084"
	@xdg-open http://localhost:8084 2>/dev/null || open http://localhost:8084 2>/dev/null || echo "Please open http://localhost:8084 in your browser"

open: ## Open application in browser
	@echo "Opening application at http://localhost:8083"
	@xdg-open http://localhost:8083 2>/dev/null || open http://localhost:8083 2>/dev/null || echo "Please open http://localhost:8083 in your browser"

dev: build open ## Build and open application (full development setup)

reset: ## Reset everything (clean + build)
	@echo "Resetting application..."
	@make clean
	@make build
	@echo "Application reset complete!"
