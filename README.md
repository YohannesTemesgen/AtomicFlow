# AtomicFlow

AtomicFlow is a Laravel-based application designed for managing workflows, tasks, and clients.

## Features

-   **Multi-Tenancy Support**: Built with [stancl/tenancy](https://tenancyforlaravel.com/).
-   **Kanban Board**: Manage tasks and projects visually.
-   **Task Tracking**: Track progress and stages of tasks.
-   **Client & Lead Management**: Organize client information and track leads.
-   **Livewire Integration**: Interactive UI components.

## Requirements

-   PHP >= 8.x
-   Composer
-   Node.js & NPM

## Installation

1.  Clone the repository:
    ```bash
    git clone <repository-url>
    ```

2.  Install PHP dependencies:
    ```bash
    composer install
    ```

3.  Install JavaScript dependencies:
    ```bash
    npm install
    ```

4.  Copy the environment file and configure your database:
    ```bash
    cp .env.example .env
    ```

5.  Generate the application key:
    ```bash
    php artisan key:generate
    ```

6.  Run migrations:
    ```bash
    php artisan migrate
    ```

7.  Start the development server:
    ```bash
    php artisan serve
    ```

## Testing

This project uses [Pest](https://pestphp.com/) for testing.

To run tests:
```bash
php artisan test
```

## License

[MIT license](https://opensource.org/licenses/MIT).
