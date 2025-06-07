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
    
    # Sprawdź czy ścieżka jest absolutna
    if [[ "$DB_DATABASE" != /* ]]; then
        echo -e "${YELLOW}Ścieżka do bazy danych SQLite nie jest absolutna. Ustawiam na katalog projektu.${NC}"
        DB_DATABASE=$(pwd)/database/database.sqlite
        sed -i "s|DB_DATABASE=.*|DB_DATABASE=$DB_DATABASE|" .env
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
        echo -e "${GREEN}✓ Utworzono plik bazy danych SQLite: $DB_DATABASE${NC}"
    else
        echo -e "${YELLOW}Plik bazy danych SQLite już istnieje: $DB_DATABASE${NC}"
    fi
fi

# Budowanie i uruchamianie kontenerów
print_header "Budowanie i uruchamianie kontenerów Docker"
docker-compose down --remove-orphans
docker-compose build
docker-compose up -d

echo -e "${GREEN}✓ Kontenery zostały zbudowane i uruchomione${NC}"

# Instalacja zależności Composer
print_header "Instalacja zależności Composer"
docker-compose exec app composer install
echo -e "${GREEN}✓ Zależności Composer zostały zainstalowane${NC}"

# Generowanie klucza aplikacji
print_header "Generowanie klucza aplikacji Laravel"
docker-compose exec app php artisan key:generate --force
echo -e "${GREEN}✓ Klucz aplikacji Laravel został wygenerowany${NC}"

# Wykonanie migracji
print_header "Wykonywanie migracji Laravel"
docker-compose exec app php artisan migrate --force
echo -e "${GREEN}✓ Migracje zostały wykonane${NC}"

# Wykonanie seederów (opcjonalnie)
print_header "Wykonywanie seederów Laravel"
docker-compose exec app php artisan db:seed --force
echo -e "${GREEN}✓ Seedy zostały wykonane${NC}"

# Czyszczenie cache
print_header "Czyszczenie cache Laravel"
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan route:clear
docker-compose exec app php artisan view:clear
echo -e "${GREEN}✓ Cache Laravel został wyczyszczony${NC}"

# Ustawienie uprawnień
print_header "Ustawianie uprawnień do katalogów"
docker-compose exec app chmod -R 777 /www/storage
docker-compose exec app chmod -R 777 /www/bootstrap/cache
echo -e "${GREEN}✓ Uprawnienia do katalogów zostały ustawione${NC}"

# Wyświetlanie informacji o dostępie
print_header "Informacje o dostępie"
echo -e "${GREEN}✓ Aplikacja jest dostępna pod adresem: ${YELLOW}http://localhost:8000${NC}"
echo -e "${GREEN}✓ Baza danych MySQL jest dostępna na porcie: ${YELLOW}3306${NC}"

# Zakończenie
print_header "Inicjalizacja zakończona pomyślnie!"
echo -e "${GREEN}Aplikacja Task Manager została pomyślnie skonfigurowana w kontenerach Docker.${NC}"
echo -e "${YELLOW}Aby wyłączyć kontenery, użyj komendy: docker-compose down${NC}"
