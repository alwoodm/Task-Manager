#!/bin/bash

cd /var/www/html

# Instalacja zależności PHP
composer install --no-interaction

# Instalacja zależności Node.js
npm install

# Generowanie klucza aplikacji, jeśli nie istnieje
php artisan key:generate --force

# Utworzenie bazy danych SQLite jeśli nie istnieje
touch database/database.sqlite

# Uruchomienie migracji
php artisan migrate --force

# W środowisku produkcyjnym budujemy assety
if [ "$APP_ENV" = "production" ]; then
  echo "Building assets for production..."
  npm run build
else
  # W środowisku deweloperskim uruchamiamy serwer Vite
  echo "Starting Vite dev server..."
  npm run dev -- --host 0.0.0.0 &
fi

# Uruchomienie serwera Laravel
echo "Starting Laravel server..."
php artisan serve --host=0.0.0.0 --port=8000

# Kontener będzie działał tak długo jak Laravel server
