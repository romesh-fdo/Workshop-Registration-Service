# Workshop Registration Management System

A Laravel application for managing workshops, attendee registrations, user roles, and registration history.

## Requirements

- PHP
- Composer
- MySQL
- Node.js and npm

## Installation

### 1. Clone the Repository

```bash
git clone <repository-url>
cd WorkshopRegistrationService
```

Replace `<repository-url>` with the actual Git repository URL.

### 2. Install Dependencies

```bash
composer install
npm install
```

### 3. Configure Environment

Copy the example environment file:

```cmd
copy .env.example .env
```

Generate the application key:

```bash
php artisan key:generate
```

Update the database configuration in `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=workshop_registration
DB_USERNAME=root
DB_PASSWORD=
```

Create the `workshop_registration` database in MySQL before continuing. Update the credentials if necessary.

### 4. Run Database Migrations

```bash
php artisan migrate
```

### 5. Run Seeders

Run the following seeders **in the specified order** after completing the migrations.

First, seed roles and permissions:

```bash
php artisan db:seed --class="Database\Seeders\RolesAndPermissionsSeeder"
```

Next, seed the initial application data:

```bash
php artisan db:seed --class="Database\Seeders\DataSeeder"
```

### 6. Build Frontend Assets

```bash
npm run build
```

### 7. Start the Application

```bash
php artisan serve
```

Open the application at:

http://127.0.0.1:8000

## Fresh Database Setup

To reset the local database and rebuild it from scratch:

```bash
php artisan migrate:fresh
php artisan db:seed --class="Database\Seeders\RolesAndPermissionsSeeder"
php artisan db:seed --class="Database\Seeders\DataSeeder"
```

**Warning:** `migrate:fresh` deletes all existing database tables and their data. Use it only when you intend to reset the database.

## Troubleshooting

### Clear Laravel Caches

```bash
php artisan optimize:clear
```

### Refresh Composer Autoloading

```bash
composer dump-autoload
```

### Check Migration Status

```bash
php artisan migrate:status
```

## Important Notes

- Run migrations before executing the seeders.
- Always run `RolesAndPermissionsSeeder` before `DataSeeder`.
- Keep `.env` files and credentials out of version control.
- Verify the seeders' contents for any default development login credentials.
