# Project Status

Date: 2026-04-13

## Current State

The repository has been scaffolded from scratch and now contains:

- a working Svelte 5 + SvelteKit static frontend
- a working Laravel 11 API backend
- Sanctum token authentication foundations
- MySQL-ready environment templates
- seeded demo data and feature tests
- architecture and implementation context docs

## Completed

### Frontend

- Created a SvelteKit static app in `frontend/`
- Enabled `adapter-static` with SPA fallback and Apache rewrite support
- Added shared design tokens, dark mode, and branded typography
- Built role-focused route shells:
  - `/`
  - `/auth/login`
  - `/cashier`
  - `/admin`
  - `/superadmin`
- Added:
  - demo session store
  - theme store
  - central API helper
  - reusable UI components
  - Bits UI tab usage in desktop dashboards

### Backend

- Created a Laravel 11 app in `backend/`
- Installed and configured Sanctum
- Added API routing and role middleware
- Added models and migrations for:
  - users
  - stores
  - products
  - orders
  - order_items
  - personal_access_tokens
- Added controllers for:
  - auth
  - dashboards
  - products
  - orders
  - stores
  - admin user management
- Added seed data for demo users, stores, products, and one example order
- Removed unused Blade/Vite backend scaffolding so the app stays API-only

### Docs

- Added `architecture.md`
- Added `project-status.md`
- Added `context.md`
- Added root and per-app environment/setup references

## Verified

Frontend:

- `npm run check`
- `npm run lint`
- `npm run build`

Backend:

- `php artisan migrate:fresh --seed`
- `php artisan test`
- `php artisan route:list --path=api`

## Seed Credentials

All seeded accounts use password: `password`

- `cashier@vanaila.test`
- `admin@vanaila.test`
- `superadmin@vanaila.test`

## Known Gaps

- Frontend login still uses a demo session store instead of the live API request
- Admin and superadmin dashboards are UI shells, not full CRUD screens yet
- Superadmin “global settings” are represented in UI structure only, not persisted in the backend yet
- Reporting endpoints are summary-level only and not yet filterable by date/store range
- No production-grade audit log, inventory movement ledger, or payment reconciliation workflow yet

## Recommended Next Iteration

1. Wire `/auth/login` to the real backend and replace demo session persistence.
2. Add full create/edit forms and table actions for products, stores, and admins.
3. Add report filters, exports, and store-specific analytics endpoints.
4. Add operational hardening: policies, audit logging, and error reporting.
