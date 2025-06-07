FROM php:8.2-cli

# Instalacja zależności systemowych
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    nodejs \
    npm \
    sqlite3

# Instalacja rozszerzeń PHP
RUN docker-php-ext-install mbstring exif pcntl bcmath gd zip
RUN docker-php-ext-install pdo_sqlite

# Instalacja Composera
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Ustawienie katalogu roboczego
WORKDIR /var/www/html

# Kopiowanie plików aplikacji
COPY . /var/www/html

# Uprawnienia do zapisu w katalogach Laravel
RUN chmod -R 777 /var/www/html/storage
RUN chmod -R 777 /var/www/html/bootstrap/cache

# Kopiowanie skryptu startowego
COPY start.sh /var/www/html/start.sh
RUN chmod +x /var/www/html/start.sh

EXPOSE 8000

CMD ["/var/www/html/start.sh"]
