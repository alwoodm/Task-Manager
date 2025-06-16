FROM php:8.3-fpm

# Argumenty dla konfiguracji użytkownika
ARG user=laravel
ARG uid=1000

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
    sqlite3 \
    libsqlite3-dev

# Instalacja rozszerzeń PHP
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip intl
RUN apt-get install -y libsqlite3-dev && docker-php-ext-install pdo_sqlite

# Instalacja rozszerzenia Redis
RUN pecl install redis && docker-php-ext-enable redis

# Instalacja Composera
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Tworzenie użytkownika systemowego
RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user && chown -R $user:$user /home/$user

# Ustawienie katalogu roboczego
WORKDIR /www

# Kopiowanie plików konfiguracyjnych PHP
COPY docker/php/local.ini /usr/local/etc/php/conf.d/local.ini

EXPOSE 9000

CMD ["php-fpm"]
