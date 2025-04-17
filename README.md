# Task Manager

A simple but powerful task management application built with Laravel and modern frontend technologies.

## Features

- User authentication and authorization
- Create, read, update, and delete tasks
- Mark tasks as completed/not completed
- Responsive design with dark mode support
- Simple and intuitive user interface

## Technologies Used

- **Backend**: Laravel 12
- **Frontend**: Tailwind CSS, Alpine.js
- **Database**: SQLite (default) / MySQL (configurable)
- **Development**: Vite for frontend assets

## Local Development Setup

### Requirements

- PHP 8.2 or higher
- Composer
- Node.js and NPM
- (Optional) Docker and Docker Compose

### Standard Setup

1. Clone the repository
   ```bash
   git clone https://github.com/yourusername/task-manager.git
   cd task-manager
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

5. Create the database
   ```bash
   touch database/database.sqlite
   php artisan migrate --seed # This will seed the database with sample tasks
   ```

6. Start the development servers
   ```bash
   # In one terminal
   php artisan serve
   
   # In another terminal
   npm run dev
   ```

7. Visit the application at http://localhost:8000

## Project Architecture

### Directory Structure

The application follows the standard Laravel directory structure with some customizations:

- `app/Models` - Contains all Eloquent models (Task, User)
- `app/Http/Controllers` - Contains all controllers for handling requests
- `app/Http/Requests` - Contains form request validation classes
- `resources/views` - Contains all Blade templates
- `resources/css` - Contains Tailwind CSS customizations
- `resources/js` - Contains Alpine.js components

### Key Components

#### Models

- `User` - Standard Laravel authentication model with tasks relationship
- `Task` - The core model representing a task with properties:
  - title - Task title
  - description - Detailed task description
  - completed - Boolean status flag
  - user_id - Foreign key to the user who owns the task

#### Controllers

- `TaskController` - Handles CRUD operations for tasks
- `DashboardController` - Manages the main dashboard display

#### Services

The application implements a service layer to separate business logic:

- `TaskService` - Manages task creation, updating, and filtering

### Database Structure

The application uses migrations to define the database schema:

- `users` - Standard Laravel authentication table
- `tasks` - Stores all user tasks with relationships
- `sessions` - Manages user sessions

## Application Features

### Authentication

- Breeze-powered authentication system
- Email verification (optional)
- Password reset functionality

### Task Management

- Task creation with form validation
- Task listing with filters (all, completed, incomplete)
- Task updating
- Task completion toggling
- Task deletion with confirmation

### UI Components

- Custom Blade components for:
  - Task cards
  - Form inputs
  - Modal dialogs
  - Alert messages

### Frontend Integration

The frontend leverages:

- Tailwind CSS for styling with custom components
- Alpine.js for interactive components
- Laravel Vite integration for asset compilation
- Dark mode toggle with browser storage persistence

## Customization

### Themes

The application supports both light and dark themes. The theme selection uses Alpine.js and is saved in the browser's local storage:

```javascript
x-data="{ darkMode: localStorage.getItem('dark') === 'true' }" 
x-init="$watch('darkMode', val => localStorage.setItem('dark', val))"
```

### Languages

Translations are stored in the `resources/lang/{locale}` directories. The application currently supports:

- English (en) - Default

To add a new language, create a new directory in `resources/lang/` with the appropriate locale code and translate the strings in:

- `app.php` - Application-specific strings
- `auth.php` - Authentication strings
- `pagination.php` - Pagination strings
- `validation.php` - Validation strings

## Extending the Application

### Adding New Task Attributes

To add new attributes to tasks:

1. Create a new migration:
   ```bash
   php artisan make:migration add_priority_to_tasks_table
   ```

2. Update the Task model with the new attribute

3. Modify the form views and controllers to handle the new attribute

### Adding New Features

The modular architecture makes it easy to add new features:

1. Create new controllers using:
   ```bash
   php artisan make:controller FeatureController --resource
   ```

2. Define routes in `routes/web.php`

3. Create corresponding views in `resources/views`

## Testing

The application includes both feature and unit tests:

```bash
# Run all tests
php artisan test

# Run specific test suite
php artisan test --testsuite=Feature

# Run with coverage report
php artisan test --coverage
```

## Artisan Commands

The application includes custom Artisan commands:

```bash
# List all available commands
php artisan list

# Cleanup expired tasks
php artisan tasks:cleanup

# Generate task report
php artisan tasks:report
```

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Credits

Created and maintained by [alwood](https://alwood.ovh).
