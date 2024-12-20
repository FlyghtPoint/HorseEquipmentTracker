# OS specific commands
ifeq ($(OS),Windows_NT)
    RM = rd /s /q
    SYMFONY = symfony.exe
else
    RM = rm -rf
    SYMFONY = symfony
endif

# Environment setup
install:
    composer install

# Development server
dev:
ifeq ($(OS),Windows_NT)
    $(SYMFONY) serve --no-tls
else
    $(SYMFONY) serve
endif

# Testing and linting
test:
    php bin/phpunit

lint:
    php vendor/bin/phpcs

# Database operations
db-init:
    php bin/console doctrine:database:create
    php bin/console doctrine:schema:create

db-migrate:
    php bin/console doctrine:migrations:migrate --no-interaction

db-fixtures:
    php bin/console doctrine:fixtures:load --no-interaction

# Production build
build:
    composer install --no-dev --optimize-autoloader

# Cleanup
clean:
    $(RM) var/cache/*
    $(RM) public/build/*

.PHONY: install dev test lint db-init db-migrate db-fixtures build clean