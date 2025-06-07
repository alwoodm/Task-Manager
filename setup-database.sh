#!/bin/bash

echo "Task Manager - Konfiguracja bazy danych"
echo "--------------------------------------"

# Ścieżka do pliku bazy danych
DB_FILE="database/database.sqlite"

# Ustawienia .env dla SQLite
ENV_SQLITE_CONFIG="DB_CONNECTION=sqlite
DB_DATABASE=$(pwd)/$DB_FILE"

# Ustawienia .env dla sterownika file
ENV_FILE_CONFIG="DB_CONNECTION=file
DB_FILE_PATH=$(pwd)/storage/app/database"

# Sprawdź, czy rozszerzenie SQLite jest dostępne
if php -r "echo extension_loaded('sqlite3') || extension_loaded('pdo_sqlite') ? 'TAK' : 'NIE';" | grep -q "TAK"; then
    echo "✓ Rozszerzenie SQLite jest dostępne w PHP!"
    echo "  Konfigurowanie bazy danych SQLite..."

    # Tworzymy pusty plik bazy danych, jeśli nie istnieje
    mkdir -p database
    touch "$DB_FILE"
    chmod 666 "$DB_FILE"
    echo "✓ Przygotowano plik bazy danych: $DB_FILE"

    # Aktualizujemy konfigurację w pliku .env
    sed -i "s/DB_CONNECTION=.*/DB_CONNECTION=sqlite/" .env
    sed -i "s#DB_DATABASE=.*#DB_DATABASE=$(pwd)/$DB_FILE#" .env
    sed -i "/DB_FILE_PATH=.*/d" .env

    echo "✓ Zaktualizowano konfigurację w pliku .env"
    
    # Wykonaj migracje
    php artisan config:clear
    echo "✓ Wyczyszczono pamięć podręczną konfiguracji"
    
    php artisan migrate --force
    echo "✓ Wykonano migracje bazy danych"
else
    echo "⚠ Rozszerzenie SQLite NIE jest dostępne w PHP!"
    echo "  Aby zainstalować rozszerzenie w Manjaro Linux, wykonaj:"
    echo "  sudo pacman -S php-sqlite"
    echo ""
    echo "  Używam alternatywnego rozwiązania opartego na plikach JSON..."

    # Przygotowanie katalogów
    DB_DIR="storage/app/database"
    mkdir -p "$DB_DIR"
    echo "✓ Utworzono katalog dla plików bazy danych: $DB_DIR"

    # Aktualizujemy konfigurację w pliku .env
    sed -i "s/DB_CONNECTION=.*/DB_CONNECTION=file/" .env
    sed -i "s#DB_DATABASE=.*#DB_FILE_PATH=$(pwd)/$DB_DIR#" .env

    echo "✓ Zaktualizowano konfigurację w pliku .env"
    
    # Przygotuj bazę danych JSON
    cat > "$DB_DIR/migrations.json" << EOF
{
    "records": [
        {"id": 1, "migration": "0001_01_01_000000_create_users_table", "batch": 1},
        {"id": 2, "migration": "0001_01_01_000001_create_cache_table", "batch": 1},
        {"id": 3, "migration": "0001_01_01_000002_create_jobs_table", "batch": 1},
        {"id": 4, "migration": "2025_04_16_193054_create_tasks_table", "batch": 1},
        {"id": 5, "migration": "2025_04_17_105415_add_priority_to_tasks_table", "batch": 1}
    ]
}
EOF
    echo "✓ Przygotowano tabelę migracji w $DB_DIR/migrations.json"
    
    # Przygotuj puste tabele
    for table in "users" "tasks" "password_reset_tokens" "sessions" "cache" "cache_locks" "failed_jobs" "job_batches"; do
        echo '{"records": []}' > "$DB_DIR/$table.json"
        echo "✓ Utworzono tabelę $table"
    done
    
    echo "✓ Przygotowano strukturę bazy danych opartej na plikach JSON"
fi

echo ""
echo "✓ Konfiguracja bazy danych zakończona! Możesz teraz uruchomić aplikację:"
echo "  php artisan serve   # Uruchamia serwer aplikacji"
echo "  npm run dev         # Uruchamia serwer Vite dla frontendowych zasobów"
