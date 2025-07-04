#!/bin/bash

# Kolorowe output-y
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # Bez koloru

# Funkcja do wyświetlania nagłówków
print_header() {
    echo -e "\n${YELLOW}==================================${NC}"
    echo -e "${YELLOW}$1${NC}"
    echo -e "${YELLOW}==================================${NC}\n"
}

# Funkcja do sprawdzania czy komenda jest dostępna
command_exists() {
    command -v "$1" >/dev/null 2>&1
}

# Sprawdzenie czy Docker jest zainstalowany
print_header "Sprawdzanie wymagań wstępnych"
if ! command_exists docker; then
    echo -e "${RED}Docker nie jest zainstalowany. Zainstaluj Docker i spróbuj ponownie.${NC}"
    exit 1
fi

if ! command_exists docker-compose; then
    echo -e "${RED}Docker Compose nie jest zainstalowany. Zainstaluj Docker Compose i spróbuj ponownie.${NC}"
    exit 1
fi

echo -e "${GREEN}✓ Docker i Docker Compose są zainstalowane${NC}"

# Przygotowanie pliku .env
print_header "Przygotowanie pliku .env"
if [ -f ".env" ]; then
    echo -e "${YELLOW}Plik .env już istnieje. Pomijanie kopiowania .env.example do .env${NC}"
else
    cp .env.example .env
    echo -e "${GREEN}✓ Plik .env.example został skopiowany do .env${NC}"
fi

# Odczytaj konfigurację bazy danych z .env
DB_CONNECTION=$(grep DB_CONNECTION .env | cut -d '=' -f2)

# Jeśli konfiguracja to SQLite, sprawdź czy plik bazy danych istnieje
if [[ "$DB_CONNECTION" == "sqlite" ]]; then
    print_header "Przygotowanie bazy danych SQLite"
    DB_DATABASE=$(grep DB_DATABASE .env | cut -d '=' -f2)
    
    # Usunięcie cudzysłowów, jeśli są
    DB_DATABASE=${DB_DATABASE//\"/}
    DB_DATABASE=${DB_DATABASE//\'/}
    
    # Sprawdź czy ścieżka jest w formacie kontenera (/www/...)
    if [[ "$DB_DATABASE" != "/www/"* ]]; then
        echo -e "${YELLOW}Ścieżka do bazy danych SQLite nie jest poprawna dla kontenera.${NC}"
        if [[ "$DB_DATABASE" == /* ]]; then
            # Absolutna ścieżka, ale nie w formacie kontenera
            DB_DATABASE_HOST="${DB_DATABASE}"
            DB_DATABASE_CONTAINER="/www/database/database.sqlite"
        else
            # Względna ścieżka
            DB_DATABASE_HOST="$(pwd)/${DB_DATABASE}"
            DB_DATABASE_CONTAINER="/www/database/database.sqlite"
        fi
        
        # Aktualizuj plik .env
        sed -i "s|DB_DATABASE=.*|DB_DATABASE=${DB_DATABASE_CONTAINER}|" .env
        echo -e "${GREEN}✓ Zaktualizowano ścieżkę do bazy danych w pliku .env: ${DB_DATABASE_CONTAINER}${NC}"
        DB_DATABASE=${DB_DATABASE_HOST}
    else
        # Ścieżka jest już w formacie kontenera, konwertuj ją z powrotem na ścieżkę hosta
        DB_DATABASE_HOST="${DB_DATABASE/\/www\//$(pwd)\/}"
        DB_DATABASE=${DB_DATABASE_HOST}
    fi
    
    # Sprawdź czy katalog istnieje
    DB_DIR=$(dirname "$DB_DATABASE")
    if [ ! -d "$DB_DIR" ]; then
        mkdir -p "$DB_DIR"
        echo -e "${GREEN}✓ Utworzono katalog dla bazy danych: $DB_DIR${NC}"
    fi
    
    # Sprawdź czy plik bazy danych istnieje
    if [ ! -f "$DB_DATABASE" ]; then
        touch "$DB_DATABASE"
        chmod 666 "$DB_DATABASE"
        echo -e "${GREEN}✓ Utworzono plik bazy danych SQLite: $DB_DATABASE${NC}"
    else
        chmod 666 "$DB_DATABASE"
        echo -e "${GREEN}✓ Plik bazy danych SQLite już istnieje: $DB_DATABASE${NC}"
    fi
fi

# Budowanie i uruchamianie kontenerów
print_header "Budowanie i uruchamianie kontenerów Docker"
docker-compose down --remove-orphans

# Tworzenie struktury katalogów dla niezbędnych plików konfiguracyjnych
mkdir -p docker/nginx/conf.d
mkdir -p docker/mysql
mkdir -p docker/php

# Tworzenie pliku konfiguracyjnego nginx jeśli nie istnieje
if [ ! -f "docker/nginx/conf.d/app.conf" ]; then
    cat > docker/nginx/conf.d/app.conf <<EOL
server {
    listen 80;
    index index.php index.html;
    error_log  /var/log/nginx/error.log;
    access_log /var/log/nginx/access.log;
    root /www/public;
    client_max_body_size 100M;

    location ~ \.php$ {
        try_files \$uri =404;
        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        fastcgi_pass app:9000;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME \$document_root\$fastcgi_script_name;
        fastcgi_param PATH_INFO \$fastcgi_path_info;
    }

    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
        gzip_static on;
    }
}
EOL
    echo -e "${GREEN}✓ Utworzono plik konfiguracyjny nginx${NC}"
fi

# Tworzenie pliku konfiguracyjnego MySQL jeśli nie istnieje
if [ ! -f "docker/mysql/my.cnf" ]; then
    cat > docker/mysql/my.cnf <<EOL
[mysqld]
general_log = 1
general_log_file = /var/log/mysql/general.log
default-authentication-plugin = mysql_native_password
EOL
    echo -e "${GREEN}✓ Utworzono plik konfiguracyjny MySQL${NC}"
fi

# Tworzenie pliku konfiguracyjnego PHP jeśli nie istnieje
if [ ! -f "docker/php/local.ini" ]; then
    cat > docker/php/local.ini <<EOL
upload_max_filesize=40M
post_max_size=40M
memory_limit=512M
max_execution_time=600
EOL
    echo -e "${GREEN}✓ Utworzono plik konfiguracyjny PHP${NC}"
fi

# Budowanie i uruchamianie
if ! docker-compose build; then
    echo -e "${RED}Błąd podczas budowania kontenerów Docker.${NC}"
    exit 1
fi

if ! docker-compose up -d; then
    echo -e "${RED}Błąd podczas uruchamiania kontenerów Docker.${NC}"
    exit 1
fi

# Lepsze sprawdzanie statusu kontenerów
echo -e "${YELLOW}Sprawdzanie statusu kontenerów...${NC}"
sleep 5 # Daj kontenerom chwilę na uruchomienie

APP_STATUS=$(docker inspect --format='{{.State.Status}}' task_manager_app 2>/dev/null)
if [ "$APP_STATUS" != "running" ]; then
    echo -e "${RED}Kontener aplikacyjny (app) nie uruchomił się poprawnie (status: $APP_STATUS).${NC}"
    echo -e "${RED}Sprawdź logi kontenerów: docker-compose logs${NC}"
    exit 1
fi

echo -e "${GREEN}✓ Kontenery zostały zbudowane i uruchomione${NC}"

# Rozwiązanie problemu z uprawnieniami Git
print_header "Konfiguracja Git dla kontenera"
docker-compose exec -T app git config --global --add safe.directory /www
echo -e "${GREEN}✓ Konfiguracja Git została zaktualizowana${NC}"

# Instalacja zależności Composer
print_header "Instalacja zależności Composer"
if ! docker-compose exec -T app composer install; then
    echo -e "${YELLOW}Uwaga: Problem z instalacją zależności Composer.${NC}"
    echo -e "${YELLOW}Próbuję wykonać instalację z opcją --ignore-platform-reqs${NC}"
    if ! docker-compose exec -T app composer install --ignore-platform-reqs; then
        echo -e "${RED}Błąd podczas instalacji zależności Composer.${NC}"
    else
        echo -e "${GREEN}✓ Zależności Composer zostały zainstalowane z opcją --ignore-platform-reqs${NC}"
    fi
else
    echo -e "${GREEN}✓ Zależności Composer zostały zainstalowane${NC}"
fi

# Instalacja zależności NPM i budowanie zasobów
print_header "Instalacja zależności NPM i budowanie zasobów"
if ! docker-compose exec -T app npm install; then
    echo -e "${YELLOW}Uwaga: Problem z instalacją zależności NPM.${NC}"
else
    echo -e "${GREEN}✓ Zależności NPM zostały zainstalowane${NC}"
    
    if ! docker-compose exec -T app npm run build; then
        echo -e "${RED}Błąd podczas budowania zasobów front-endowych.${NC}"
    else
        echo -e "${GREEN}✓ Zasoby front-endowe zostały zbudowane${NC}"
    fi
fi

# Generowanie klucza aplikacji
print_header "Generowanie klucza aplikacji Laravel"
if ! docker-compose exec -T app php artisan key:generate --force; then
    echo -e "${YELLOW}Uwaga: Problem z generowaniem klucza aplikacji.${NC}"
else
    echo -e "${GREEN}✓ Klucz aplikacji Laravel został wygenerowany${NC}"
fi

# Czyszczenie cache konfiguracji przed migracją
docker-compose exec -T app php artisan config:clear

# Wykonanie migracji
print_header "Wykonywanie migracji Laravel"
if ! docker-compose exec -T app touch database/database.sqlite &>/dev/null; then
    echo -e "${YELLOW}Uwaga: Nie można utworzyć pliku bazy danych w kontenerze.${NC}"
fi

if ! docker-compose exec -T app php artisan migrate --force; then
    echo -e "${YELLOW}Uwaga: Problemy podczas wykonywania migracji Laravel.${NC}"
    echo -e "${YELLOW}Sprawdź poprawność konfiguracji bazy danych.${NC}"
    echo -e "${YELLOW}Spróbuj ręcznie: docker-compose exec app php artisan migrate --force${NC}"
else
    echo -e "${GREEN}✓ Migracje zostały wykonane${NC}"
fi

# Wykonanie seederów (opcjonalnie)
print_header "Wykonywanie seederów Laravel"
if ! docker-compose exec -T app php artisan db:seed --force; then
    echo -e "${YELLOW}Ostrzeżenie: Problemy podczas wykonywania seederów Laravel.${NC}"
else
    echo -e "${GREEN}✓ Seedy zostały wykonane${NC}"
fi

# Czyszczenie cache
print_header "Czyszczenie cache Laravel"
docker-compose exec -T app php artisan config:clear || echo -e "${YELLOW}Uwaga: Problem z czyszczeniem konfiguracji${NC}"
docker-compose exec -T app php artisan cache:clear || echo -e "${YELLOW}Uwaga: Problem z czyszczeniem cache${NC}"
docker-compose exec -T app php artisan route:clear || echo -e "${YELLOW}Uwaga: Problem z czyszczeniem tras${NC}"
docker-compose exec -T app php artisan view:clear || echo -e "${YELLOW}Uwaga: Problem z czyszczeniem widoków${NC}"
echo -e "${GREEN}✓ Cache Laravel został wyczyszczony${NC}"

# Ustawienie uprawnień
print_header "Ustawianie uprawnień do katalogów"
docker-compose exec -T app chmod -R 777 storage || echo -e "${YELLOW}Uwaga: Problem z ustawieniem uprawnień dla storage${NC}"
docker-compose exec -T app chmod -R 777 bootstrap/cache || echo -e "${YELLOW}Uwaga: Problem z ustawieniem uprawnień dla bootstrap/cache${NC}"
echo -e "${GREEN}✓ Uprawnienia do katalogów zostały ustawione${NC}"

# Wyświetlanie informacji o dostępie
print_header "Informacje o dostępie"
echo -e "${GREEN}✓ Aplikacja jest dostępna pod adresem: ${YELLOW}http://localhost:8001${NC}"
echo -e "${GREEN}✓ Baza danych MySQL jest dostępna na porcie: ${YELLOW}3306${NC}"

# Zakończenie
print_header "Inicjalizacja zakończona pomyślnie!"
echo -e "${GREEN}Aplikacja Task Manager została pomyślnie skonfigurowana w kontenerach Docker.${NC}"
echo -e "${YELLOW}Aby wyłączyć kontenery, użyj komendy: docker-compose down${NC}"
