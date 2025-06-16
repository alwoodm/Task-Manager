# Task Manager

## Project Description

Task Manager is a robust web application built with Laravel 12 that helps users organize and track their tasks efficiently. The application provides a modern, intuitive, and responsive interface for managing personal tasks with various priority levels.

### Main Features

- **User Authentication**: Secure registration and login system powered by Laravel Breeze
- **Task Management**: Create, read, update, and delete tasks
- **Task Status Tracking**: Mark tasks as completed/not completed
- **Task Prioritization**: Assign low, medium, or high priority to tasks
- **Responsive Design**: Works seamlessly across desktop and mobile devices
- **Dark Mode Support**: Toggle between light and dark themes for comfortable viewing

### Technologies Used

**Backend**:
- PHP 8.2+
- Laravel 12
- SQLite (default) / MySQL (configurable)
- Redis (optional, for cache and session management)

**Frontend**:
- Tailwind CSS for styling
- Alpine.js for interactive components
- Blade templating engine

**Development & Deployment**:
- Docker and Docker Compose for containerization
- Vite for frontend asset compilation
- Laravel Breeze for authentication scaffolding

## Installation and Setup Instructions

### System Requirements

- PHP 8.2 or higher
- Composer
- Node.js and NPM
- PHP SQLite extension (for default database)
- Docker and Docker Compose (for containerized setup)

### Required PHP Extensions

- `pdo_sqlite` (for SQLite database)
- `pdo_mysql` (for MySQL database, if used)
- Other standard Laravel extensions:
  - BCMath
  - Ctype
  - Fileinfo
  - JSON
  - Mbstring
  - OpenSSL
  - PDO
  - Tokenizer
  - XML

### Standard Setup (Local Development)

1. Clone the repository
   ```bash
   git clone [repository-url]
   cd Task_Menger
   ```

2. Install PHP dependencies
   ```bash
   composer install
   ```

3. Install JavaScript dependencies
   ```bash
   npm install
   ```

4. Create and configure environment file
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. Set up the database
   - For SQLite (default):
     ```bash
     touch database/database.sqlite
     php artisan migrate
     ```
   - For MySQL:
     - Configure MySQL connection in .env file
     - Run migrations:
       ```bash
       php artisan migrate
       ```

6. Start the development servers
   ```bash
   npm run dev    # Start Vite development server
   php artisan serve    # Start Laravel development server
   ```

7. Visit the application at http://localhost:8000

### Docker Setup

1. Clone the repository
   ```bash
   git clone [repository-url]
   cd Task_Menger_Final
   ```

2. Configure the environment file
   ```bash
   cp .env.example .env
   ```

3. Run the Docker initialization script
   ```bash
   chmod +x docker-init.sh
   ./docker-init.sh
   ```

4. Access the application at http://localhost:8000

## User Guide

### Registration and Authentication

1. Navigate to the registration page by clicking "Register" in the top navigation bar
2. Fill in your name, email, and password
3. Submit the registration form
4. You will be automatically logged in and redirected to the task list

### Task Management

#### Viewing Tasks
- Upon login, you will be redirected to the task list page
- All your tasks will be displayed with their title, description, priority level, and status
- Completed tasks appear with a strikethrough text and green background

#### Creating Tasks
1. Click the "Add New Task" button on the task list page
2. Fill in the task details:
   - Title (required)
   - Description (optional)
   - Priority (Low, Medium, High)
3. Click "Add Task" to save

#### Editing Tasks
1. Find the task you want to edit in the list
2. Click the pencil (edit) icon
3. Update the task details
4. Click "Update Task" to save changes

#### Completing Tasks
1. Find the task you want to mark as complete
2. Click the checkmark icon to toggle the completion status
3. The task appearance will change to indicate its status

#### Deleting Tasks
1. Find the task you want to delete
2. Click the trash (delete) icon
3. Confirm the deletion when prompted

### User Interface Features

#### Dark Mode
- Click the sun/moon icon in the bottom-right corner to toggle between light and dark modes
- Your preference is saved in the browser and will persist across sessions

#### Responsive Design
- The application is fully responsive and adapts to different screen sizes
- Mobile users can access all features with an optimized interface

## Architecture Overview

### Directory Structure

```
Task_Manager/
├── app/                                # Application core
│   ├── Models/                         # Data models
│   │   ├── Task.php                    # Task model
│   │   └── User.php                    # User model
│   ├── Http/
│   │   ├── Controllers/                # Request handlers
│   │   │   └── TaskController.php      # Task CRUD operations
│   │   └── Requests/                   # Form validation
│   └── Providers/                      # Service providers
├── database/
│   ├── migrations/                     # Database structure
│   │   ├── create_users_table.php
│   │   ├── create_tasks_table.php
│   │   └── add_priority_to_tasks_table.php
│   ├── factories/                      # Model factories for testing
│   └── seeders/                        # Database seeders
├── resources/
│   ├── views/                          # Blade templates
│   │   ├── tasks/                      # Task-related views
│   │   │   ├── index.blade.php         # Task list
│   │   │   ├── create.blade.php        # Task creation form
│   │   │   ├── edit.blade.php          # Task editing form
│   │   │   └── show.blade.php          # Task details
│   │   ├── layouts/                    # Template layouts
│   │   └── components/                 # Reusable UI components
│   ├── css/                            # Stylesheets
│   └── js/                             # JavaScript files
├── routes/                             # Application routes
│   ├── web.php                         # Web routes
│   └── auth.php                        # Authentication routes
└── docker/                             # Docker configuration files
    ├── mysql/                          # MySQL configuration
    ├── nginx/                          # Nginx configuration
    └── php/                            # PHP configuration
```

### Database Schema

#### Users Table
| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary key |
| name | varchar | User's name |
| email | varchar | User's email address (unique) |
| password | varchar | Encrypted password |
| created_at | timestamp | Creation timestamp |
| updated_at | timestamp | Last update timestamp |

#### Tasks Table
| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary key |
| user_id | bigint | Foreign key to users table |
| title | varchar | Task title |
| description | text | Task description (nullable) |
| is_completed | boolean | Completion status |
| priority | integer | Task priority (1=Low, 2=Medium, 3=High) |
| created_at | timestamp | Creation timestamp |
| updated_at | timestamp | Last update timestamp |

### Component Interactions

1. **Authentication Flow**:
   - User submits login credentials
   - Laravel authenticates via the AuthenticatedSessionController
   - Upon successful authentication, user is redirected to task list

2. **Task Management Flow**:
   - TaskController handles all CRUD operations
   - Tasks are associated with users through a one-to-many relationship
   - Access control ensures users can only manage their own tasks

3. **Frontend Integration**:
   - Backend renders Blade templates
   - Alpine.js provides reactivity for UI elements
   - Tailwind CSS handles styling and responsive design

## Developer Instructions

### Setting Up Development Environment

1. Install the required tools:
   - PHP 8.2+
   - Composer
   - Node.js and NPM
   - SQLite or MySQL

2. Follow the installation instructions in the previous section

3. Configure your IDE:
   - Enable PHP and Laravel extensions
   - Configure code style to match Laravel standards

### Adding New Features

#### Adding New Task Attributes

1. Create a new migration:
   ```bash
   php artisan make:migration add_new_field_to_tasks_table
   ```

2. Define the migration schema and run it:
   ```php
   // In the migration file
   Schema::table('tasks', function (Blueprint $table) {
       $table->string('new_field')->nullable()->after('description');
   });
   ```
   ```bash
   php artisan migrate
   ```

3. Update the Task model with the new attribute:
   ```php
   // In app/Models/Task.php
   protected $fillable = [
       'title',
       'description',
       'user_id',
       'is_completed',
       'priority',
       'new_field', // Added new field
   ];
   ```

4. Modify the form views and controllers to handle the new attribute

#### Creating New Controllers

```bash
php artisan make:controller NewFeatureController --resource
```

### Testing

Run the application tests using:

```bash
# Run all tests
php artisan test

# Run specific test suite
php artisan test --testsuite=Feature

# Run with coverage report
php artisan test --coverage
```

### Deployment Process

1. **Prerequisites**:
   - Server with PHP 8.2+
   - Composer
   - Web server (Nginx or Apache)
   - Database (SQLite or MySQL)

2. **Deployment Steps**:
   - Clone the repository to your production server
   - Install dependencies: `composer install --no-dev --optimize-autoloader`
   - Copy example environment file: `cp .env.example .env`
   - Configure environment variables for production
   - Generate application key: `php artisan key:generate`
   - Run database migrations: `php artisan migrate --force`
   - Compile assets: `npm install && npm run build`
   - Configure web server to point to the public directory
   - Set proper permissions on storage and bootstrap/cache directories

3. **Docker Deployment**:
   - Use the included docker-compose.yml for containerized deployment
   - Configure environment variables as needed
   - Run: `docker-compose up -d`

## FAQ and Common Issues

### General

**Q: How do I reset my password?**  
A: Click "Forgot your password?" on the login page and follow the instructions sent to your email.

**Q: Can I share tasks with other users?**  
A: The current version doesn't support task sharing. Each user can only see their own tasks.

**Q: Is there a mobile app available?**  
A: No native mobile app is available, but the web interface is fully responsive and works well on mobile devices.

### Technical Issues

**Q: The application is showing database connection errors**  
A: 
1. Verify your database configuration in the .env file
2. For SQLite, ensure the database file exists and is writable
3. For MySQL, check that the database, user, and password are correctly configured

**Q: Dark mode isn't working properly**  
A: 
1. Make sure JavaScript is enabled in your browser
2. Clear your browser cache and cookies
3. Check your browser's localStorage settings

**Q: How do I enable Redis for cache and sessions?**  
A:
1. Install Redis on your server or use the Docker Redis container
2. Update your .env file with:
   ```
   CACHE_DRIVER=redis
   SESSION_DRIVER=redis
   REDIS_HOST=127.0.0.1
   REDIS_PORT=6379
   ```

## Next Steps for Documentation Development

1. **API Documentation**: Create comprehensive documentation for RESTful API endpoints (when implemented)
2. **Localization Guide**: Instructions for adding new languages and translating the interface
3. **Advanced Configuration**: Detailed guide for advanced server and application configuration
4. **Performance Tuning**: Tips and best practices for optimizing application performance
5. **Security Hardening**: Guidelines for enhancing application security in production environments
