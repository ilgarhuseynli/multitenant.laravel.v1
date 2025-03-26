# Laravel 12 Multi-Tenant API

## Project Overview
This project is a multi-tenant API built using Laravel 12 with the `stancl/tenancy` package for multi-database tenancy. The API provides endpoints for managing orders, invoices, estimates, calls, users, roles, permissions, customers, and cron jobs.

## Technologies Used
- **PHP**: ^8.2
- **Laravel**: ^12.0
- **stancl/tenancy**: ^3.9
- **MySQL**: Database support
- **Laravel Sanctum**: Authentication
- **Laravel Queue**: Background job processing

## Features
- Multi-tenancy with separate databases for each tenant
- Role-based access control (RBAC)
- User and customer management
- Orders, invoices, and estimates management
- Call tracking and logging
- Scheduled cron jobs for automation
- API authentication using Laravel Sanctum

## Installation

### Prerequisites
Ensure you have the following installed:
- PHP 8.2+
- Composer
- MySQL
- Laravel CLI

### Setup Steps
1. **Clone the repository:**
   ```sh
   git clone https://github.com/ilgarhuseynli/multitenant.laravel.v1.git
   cd multitenant.laravel.v1.git
   ```

2. **Install dependencies:**
   ```sh
   composer install
   ```

3. **Copy the environment file:**
   ```sh
   cp .env.example .env
   ```

4. **Generate the application key:**
   ```sh
   php artisan key:generate
   ```

5. **Configure the database:**
   Edit the `.env` file and update database credentials.

6. **Run migrations:**
   ```sh
   php artisan migrate
   ```

7. **Set up tenancy:**
   ```sh
   php artisan tenancy:install
   ```

8. **Run the development server:**
   ```sh
   php artisan serve
   ```

## API Authentication
This project uses Laravel Sanctum for authentication. To obtain an access token:
```sh
POST /api/login
```

## Running Tests
```sh
php artisan test
```

## Deployment
1. **Optimize for production:**
   ```sh
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```
2. **Queue Worker:**
   ```sh
   php artisan queue:work
   ```
 
