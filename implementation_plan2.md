# Wire Auth, Database & Frontend Integration

The frontend login form already calls `apiRequest('/auth/login')` and the session store persists tokens, but the rest of the app still runs entirely on mock data. This plan makes everything live.

## User Review Required

> [!IMPORTANT]
> After this plan executes, mock data buttons on the login page will remain as a development fallback, but every operational page (cashier, admin, superadmin) will fetch real data from the Laravel API. Pages will show empty/loading states if the backend isn't running.

> [!WARNING]
> The backend `.env` currently uses `DB_CONNECTION=sqlite`. This plan switches to MySQL as specified in `architecture.md`. You'll need a local MySQL server with a `vanaila_pos` database available.

## Proposed Changes

### Phase A — Database & Backend Environment

#### [MODIFY] `backend/.env`
- Switch `DB_CONNECTION` to `mysql`
- Set `DB_DATABASE=vanaila_pos`, credentials for local dev
- Add `FRONTEND_URL=http://localhost:5173` for CORS

#### Verification step
```bash
cd backend
php artisan migrate:fresh --seed
php artisan serve
```

---

### Phase B — API Client Hardening

#### [MODIFY] `frontend/src/lib/api/client.ts`
- Parse Laravel validation error JSON (`{message, errors}`) instead of raw text
- Add `401` handling: auto-clear session and redirect to `/auth/login`
- Export helper type for paginated responses (`ApiPaginatedResponse<T>`)

---

### Phase C — Session & Auth Lifecycle

#### [MODIFY] `frontend/src/lib/stores/session.svelte.ts`
- Add `logout()` method that calls `POST /auth/logout` on the API before clearing local state
- Add `validate()` method that calls `GET /auth/me` to verify a stored token is still valid

#### [MODIFY] `frontend/src/routes/+layout.svelte`
- On app mount, call `session.validate()` to catch expired/revoked tokens early

#### [NEW] `frontend/src/lib/guards/auth.ts`
- Export `requireAuth(session, role?)` utility
- Redirects to `/auth/login` if no session, or to `/` if role mismatch
- Used by protected layout components (`cashier/+layout.svelte`, `admin/+layout.svelte`, `superadmin/+layout.svelte`)

#### [MODIFY] `frontend/src/routes/cashier/+layout.svelte`
- Call `requireAuth(session, 'cashier')` on mount

#### [MODIFY] `frontend/src/routes/admin/+layout.svelte`
- Call `requireAuth(session, 'admin')` on mount

#### [MODIFY] `frontend/src/routes/superadmin/+layout.svelte`
- Call `requireAuth(session, 'superadmin')` on mount

---

### Phase D — Cashier Page: Live Data

#### [MODIFY] `frontend/src/routes/cashier/+page.svelte`
- Replace `cashierProducts` mock import with `onMount` call to `GET /products`
- Replace `cashierSummary` mock import with `onMount` call to `GET /cashier/overview`
- Map API response (`price_cents`, `stock_quantity`) to frontend types (`price` in dollars, `stock`)
- Add loading skeleton state while fetching

---

### Phase E — Admin Pages: Live Data

#### [MODIFY] `frontend/src/routes/admin/+page.svelte`
- Replace `adminMetrics` mock with `GET /admin/overview` API call
- Map backend response keys (`products_active`, `todays_revenue_cents`) to MetricCard props

#### [MODIFY] `frontend/src/routes/admin/catalog/+page.svelte`
- Replace `adminProducts` mock with `GET /products` API call

#### [MODIFY] `frontend/src/routes/admin/orders/+page.svelte`
- Replace `adminOrders` mock with `GET /orders` API call

---

### Phase F — Superadmin Pages: Live Data

#### [MODIFY] `frontend/src/routes/superadmin/+page.svelte`
- Replace `superadminMetrics` mock with `GET /superadmin/overview` API call

#### [MODIFY] `frontend/src/routes/superadmin/stores/+page.svelte`
- Replace `superadminStores` mock with `GET /superadmin/stores` API call

#### [MODIFY] `frontend/src/routes/superadmin/admins/+page.svelte`
- Replace `superadminAdminRoster` mock with `GET /superadmin/admins` API call

---

### Phase G — Login Page Cleanup

#### [MODIFY] `frontend/src/routes/auth/login/+page.svelte`
- Improve error display: parse Laravel validation `errors` object for field-level messages
- If user is already logged in (session exists), redirect to their role dashboard immediately

## Verification Plan

### Automated Tests
```bash
cd backend && ./vendor/bin/phpunit
cd frontend && npm run check && npm run lint
```

### Manual Verification (end-to-end)
1. Start backend: `cd backend && php artisan serve`
2. Start frontend: `cd frontend && npm run dev`
3. Open `http://localhost:5173` → should redirect to login
4. Login with `cashier@vanaila.test` / `password` → should land on `/cashier` with real product data
5. Logout → should clear token and redirect to login
6. Login with `admin@vanaila.test` → verify `/admin` shows real metrics
7. Login with `superadmin@vanaila.test` → verify `/superadmin` shows real stores/admins
8. Close tab, reopen → token should be validated via `GET /auth/me`
