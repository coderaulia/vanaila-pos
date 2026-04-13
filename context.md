# Context

## Product Intent

Vanaila POS is a role-driven point-of-sale web application intended for:

- a customer-facing cashier terminal that feels fast on mobile/tablet
- an admin dashboard for store operations on desktop
- a superadmin dashboard for multi-store governance

The project must work with Hostinger Business shared hosting, which means:

- no Node.js process on the server
- frontend must be exported as static files
- backend must run as standard PHP/Laravel

## Non-Negotiable Constraints

- Frontend stack: Svelte 5 + SvelteKit
- Frontend deployment: static output only
- Backend stack: Laravel 11
- Backend style: REST API only, no Blade templates
- Database: MySQL 8
- Auth: Sanctum bearer token flow for SPA requests
- UI direction:
  - mobile-first cashier
  - desktop-first admin/superadmin
  - clean modern component-driven system
  - dark mode support

## What Exists Right Now

### Frontend

- shared app header and theme toggle
- route map for each role
- design tokens and visual system
- demo session handling for role walkthroughs
- placeholder-ready API client for future real auth integration

### Backend

- auth endpoint for token creation
- me/logout endpoints
- health endpoint
- dashboard summary endpoints
- product listing/create/update endpoints
- order listing/create endpoints
- store listing/create/update endpoints
- admin listing/create/update endpoints

## Current Working Assumptions

- One Laravel API serves all frontend roles
- Role access is enforced server-side through middleware
- Product prices are stored in integer cents in the backend
- Static frontend routes are protected at the UX layer, while API routes are protected at the server layer
- Hostinger Apache rewrite rules are available for SPA routing

## Local Environment Notes

### Frontend

- `frontend/.env.example` defines `PUBLIC_API_BASE_URL`
- local dev uses Vite/SvelteKit
- production deploy uploads `frontend/build/`

### Backend

- `backend/.env.example` is configured for MySQL 8
- PHPUnit uses in-memory SQLite for fast automated tests
- local scaffold was verified with Laravel CLI and migrations

## Important Implementation Notes

- The frontend currently demonstrates the role experience without waiting on a full live-auth integration.
- The backend already supports real login via Sanctum and returns bearer tokens.
- Replacing the frontend demo session is now an integration task, not a structural task.
- Shared design rules live in `frontend/src/app.css`, so future UI additions should reuse tokens before introducing new colors or spacing systems.

## Suggested Team Workflow

1. Keep API contracts in `backend/routes/api.php` and controller responses stable.
2. When adding UI, route new frontend fetches through `frontend/src/lib/api/client.ts`.
3. Prefer extending existing design tokens/components before introducing one-off styles.
4. Add a backend feature test for each new protected workflow.
