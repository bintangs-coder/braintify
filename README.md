# Braintify

Learn Fast. Teach Smart. Grow Together.

## Requirements
- PHP 8.2+
- Composer
- SQLite or MySQL

## Installation

```bash
# Install dependencies
composer install

# Setup environment
cp .env.example .env

# Generate key
php artisan key:generate

# Create SQLite database (or use MySQL)
touch database/database.sqlite

# Run migrations & seeders
php artisan migrate
php artisan db:seed

# Start development server
php artisan serve
```

## Features
- Skill Exchange - Trade skills for free
- Mentor Booking - Book sessions with experts
- Micro Services - Get quick help with tasks
- Reviews & Ratings

## License
MIT
