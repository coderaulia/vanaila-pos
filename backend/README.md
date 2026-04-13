# Backend

Laravel 11 REST API for Vanaila POS.

## Responsibilities

- Sanctum token authentication
- role-based route protection
- products, orders, stores, and admin management APIs
- MySQL-ready configuration for shared hosting deployment

## Local Run

```bash
cp .env.example .env
composer install
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

## Validation

```bash
php artisan migrate:fresh --seed
php artisan test
php artisan route:list --path=api
```

## Seed Accounts

Password for all seeded accounts:

- `cashier@vanaila.test`
- `admin@vanaila.test`
- `superadmin@vanaila.test`
