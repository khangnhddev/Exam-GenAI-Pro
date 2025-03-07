# AI Certificate Platform

A modern certification platform built with Laravel and Vue.js that allows users to take exams and earn certificates.

## Prerequisites

Before you begin, ensure you have the following installed:
- PHP >= 8.1
- Composer
- Node.js >= 16
- PostgreSQL >= 14
- npm or yarn

## Installation Guide

### 1. Clone the Repository
```bash
git clone <repository-url>
cd Frontend-Gen-AI
```

### 2. Install Dependencies
```bash
# Install PHP dependencies
composer install

# Install Node dependencies
npm install
```

### 3. Environment Setup
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Configure Database
Update your `.env` file with your PostgreSQL credentials:
```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 5. Database Setup
```bash
# Create database
createdb your_database_name

# Run migrations
php artisan migrate

# Seed the database (optional)
php artisan db:seed
```

### 6. Storage Setup
```bash
# Create storage link
php artisan storage:link
```

### 7. Start Development Servers
```bash
# Start Laravel server
php artisan serve

# In a new terminal, start Vite server
npm run dev
```

Your application should now be running at:
- Laravel: http://localhost:8000
- Vite: http://localhost:5173

## Common Issues & Solutions

### Storage Permissions
If you encounter permission issues:
```bash
chmod -R 775 storage bootstrap/cache
```

### Database Connection Issues
1. Ensure PostgreSQL service is running:
```bash
# Start PostgreSQL service
brew services start postgresql@14
```

2. Verify database exists:
```bash
psql -l
```

### Vite Server Issues
If you encounter issues with the Vite server:
```bash
# Clear npm cache
npm cache clean --force

# Remove node modules and reinstall
rm -rf node_modules package-lock.json
npm install
```

## Additional Commands

### Database Management
```bash
# Fresh migration with seeds
php artisan migrate:fresh --seed

# Only rollback
php artisan migrate:rollback

# Refresh migrations
php artisan migrate:refresh
```

### Cache Management
```bash
# Clear application cache
php artisan cache:clear

# Clear route cache
php artisan route:clear

# Clear config cache
php artisan config:clear

# Clear all caches
php artisan optimize:clear
```

## Development

### Code Style
This project follows PSR-12 coding standards. To check your code:
```bash
./vendor/bin/pint
```

### Testing
```bash
# Run all tests
php artisan test

# Run specific test
php artisan test --filter TestName
```

## Production Deployment

Before deploying to production:
```bash
# Install dependencies
composer install --optimize-autoloader --no-dev

# Optimize configuration loading
php artisan config:cache

# Optimize route loading
php artisan route:cache

# Build frontend assets
npm run build
```
