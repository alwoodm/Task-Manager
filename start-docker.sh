#!/bin/bash

# Czeka na dostępność bazy danych
echo "Waiting for database to be ready..."
# Tutaj można dodać kod sprawdzający dostępność MySQL

# Instaluje zależności
if [ -f "composer.json" ]; then
    composer install --no-interaction
fi

# Wykonuje migracje
php artisan migrate --force

# Uruchamia serwer PHP
php artisan serve --host=0.0.0.0 --port=8000
