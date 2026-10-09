# Workshop Registration Management System

A Laravel application for managing workshops, attendee registrations, user roles, waitlists, and registration history.

## Requirements

- PHP
- Composer
- MySQL
- Node.js and npm

## Installation

### 1. Clone the repository

```bash
git clone <repository-url>
cd WorkshopRegistrationService
```

### 2. Install dependencies

```bash
composer install
npm install
```

### 3. Configure the environment

On Windows:

```cmd
copy .env.example .env
```

Generate the application key:

```bash
php artisan key:generate
```

Configure your database in `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=workshop_registration
DB_USERNAME=root
DB_PASSWORD=
```

Create the database in MySQL before proceeding.

### 4. Run migrations

```bash
php artisan migrate
```

### 5. Run seeders

Execute the seeders in this order:

```bash
php artisan db:seed --class="Database\Seeders\RolesAndPermissionsSeeder"
php artisan db:seed --class="Database\Seeders\DataSeeder"
```

### 6. Build frontend assets

```bash
npm run build
```

### 7. Start the application

```bash
php artisan serve
```

Open the welcome page:

http://127.0.0.1:8000

Open the administration and login page:

http://127.0.0.1:8000/admin/login

## Sample Login Credentials

The following accounts are created by the sample data seeder.

| Role | Email | Password |
|---|---|---|
| Admin | `admin@example.com` | `Password123!` |
| Manager | `manager@example.com` | `Password123!` |
| Staff | `staff@example.com` | `Password123!` |

All three roles use the same login URL:

http://127.0.0.1:8000/admin/login

Access to application features depends on the user's assigned role and permissions.

**Security:** These are development-only credentials. Change or remove them before deploying the application to production.

## Fresh Database Setup

To rebuild a local development database from scratch:

```bash
php artisan migrate:fresh
php artisan db:seed --class="Database\Seeders\RolesAndPermissionsSeeder"
php artisan db:seed --class="Database\Seeders\DataSeeder"
```

**Warning:** `migrate:fresh` deletes all existing database tables and data.

## Troubleshooting

Clear Laravel caches:

```bash
php artisan optimize:clear
```

Refresh Composer autoloading:

```bash
composer dump-autoload
```

Check migration status:

```bash
php artisan migrate:status
```

## Implementation Notes

- Laravel provides the application framework and database transaction support.
- Filament simplifies administrative forms, tables, and actions.
- MySQL stores workshops, registrations, users, and their relationships.
- Spatie Laravel Permission manages roles and permissions.
- Database transactions and workshop row-level locking prevent concurrent requests from exceeding capacity when using a database engine that supports the required locking semantics.
- Cancelled registrations remain in the database for audit history.
- When an active registration is cancelled, the earliest waitlisted attendee is promoted automatically.
