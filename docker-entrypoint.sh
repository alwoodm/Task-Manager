#!/bin/bash
set -e

# Ensure storage directories are writable
find /var/www/html/storage -type d -exec chmod 775 {} \;
find /var/www/html/storage -type f -exec chmod 664 {} \;
find /var/www/html/bootstrap/cache -type d -exec chmod 775 {} \;

# Clear any cached data
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# Run migrations if database is available
php artisan migrate --force

# Execute CMD
exec "$@"
