# Vanaila POS

Monorepo scaffold for a static SvelteKit frontend and a Laravel 11 REST API backend.

Docs:

- [`architecture.md`](./architecture.md)
- [`project-status.md`](./project-status.md)
- [`context.md`](./context.md)

Project layout:

- `frontend/` - Svelte 5 + SvelteKit static SPA for cashier, admin, and superadmin
- `backend/` - Laravel 11 API with Sanctum token auth, MySQL-ready config, and seed data

## Local Database Setup (MySQL)

1. Start MySQL locally (`127.0.0.1:3306`) and ensure a user in `backend/.env` has create-database access.
2. Run:

```bash
cd backend
./scripts/setup-local-db.sh
```

3. Start API:

```bash
php artisan serve
```

Seeded login users (password: `password`):

- `cashier@vanaila.test`
- `admin@vanaila.test`
- `superadmin@vanaila.test`

## Backend Endpoint QA Script

Run the API smoke suite against a running backend:

```bash
cd backend
./scripts/qa-endpoints.sh
```

Optional environment overrides:

- `API_BASE_URL` (default: `http://127.0.0.1:8000/api/v1`)
- `QA_CASHIER_EMAIL` / `QA_CASHIER_PASSWORD`
- `QA_ADMIN_EMAIL` / `QA_ADMIN_PASSWORD`
- `QA_SUPERADMIN_EMAIL` / `QA_SUPERADMIN_PASSWORD`
