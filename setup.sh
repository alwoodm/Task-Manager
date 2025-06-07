#!/bin/bash

echo "Task Manager Setup Script"
echo "========================="
echo

# Check for PHP
if ! command -v php &> /dev/null; then
    echo "Error: PHP is not installed or not in your PATH"
    echo "Please install PHP 8.2 or higher and try again"
    exit 1
fi

# Check for Composer
if ! command -v composer &> /dev/null; then
    echo "Error: Composer is not installed or not in your PATH"
    echo "Please install Composer and try again"
    exit 1
fi

# Check for SQLite extension
if ! php -m | grep -q sqlite; then
    echo "Warning: SQLite extension is not installed in PHP"
    echo "You will need to install it:"
    echo "  Ubuntu/Debian: sudo apt-get install php-sqlite3"
    echo "  CentOS/RHEL: sudo yum install php-sqlite3"
    echo "  macOS: brew install php"
    echo
    read -p "Continue with limited functionality? (y/n) " -n 1 -r
    echo
    if [[ ! $REPLY =~ ^[Yy]$ ]]; then
        exit 1
    fi
fi

# Install PHP dependencies
echo "Installing PHP dependencies..."
composer install

# Install JavaScript dependencies (if package.json exists)
if [ -f "package.json" ]; then
    if command -v npm &> /dev/null; then
        echo "Installing JavaScript dependencies..."
        npm install
    else
        echo "Warning: npm not found. Skipping JavaScript dependencies."
        echo "You'll need to run 'npm install' manually."
    fi
fi

# Create .env file if it doesn't exist
if [ ! -f ".env" ]; then
    echo "Creating .env file..."
    cp .env.example .env || echo "APP_NAME=\"Task Manager\"" > .env
fi

# Run the update command
echo "Setting up the application..."
php artisan update

echo
echo "Setup completed!"
echo "You can now start the application:"
echo "  - Run 'php artisan serve' to start the web server"
echo "  - Run 'npm run dev' in another terminal for frontend assets"

exit 0
