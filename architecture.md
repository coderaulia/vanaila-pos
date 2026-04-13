# Vanaila POS Architecture

## 1. System Overview

Vanaila POS is a split-stack web application designed for Hostinger Business shared hosting, where Node.js is not available on the server.

- Frontend: Svelte 5 + SvelteKit, compiled to static assets with `adapter-static`
- Backend: Laravel 11, REST API only, no Blade rendering
- Database: MySQL 8
- Authentication: Laravel Sanctum using bearer tokens for SPA requests
- Deployment target:
  - `frontend/build/*` -> Hostinger `public_html/`
  - `backend/*` -> Hostinger `api/`

The architecture deliberately separates presentation from business logic:

- SvelteKit owns route shells, client state, theme, and responsive UI composition
- Laravel owns authentication, authorization, persistence, validation, and business workflows
- MySQL stores all operational data

## 2. Role Model

### Cashier

- Device profile: mobile-first tablet or handheld
- Responsibilities:
  - search products
  - create orders
  - confirm payment
  - monitor current shift activity

### Admin

- Device profile: desktop-first dashboard
- Responsibilities:
  - manage products
  - track daily sales
  - review stock and operations

### Superadmin

- Device profile: desktop-first control tower
- Responsibilities:
  - manage stores
  - manage admins/cashiers
  - oversee platform-wide settings and health

## 3. High-Level Topology

```text
Cashier/Admin/Superadmin Browser
            |
            v
  SvelteKit Static Frontend
  public_html/
            |
            | Bearer Token API Requests
            v
      Laravel 11 REST API
           /api
            |
            v
          MySQL 8
```

## 4. Repository Structure

```text
/vanaila-pos
  architecture.md
  context.md
  project-status.md
  README.md
  /frontend
    .env.example
    package.json
    svelte.config.js
    /src
      app.css
      /lib
        /api
          client.ts
        /components
          /layout
            AppHeader.svelte
          /ui
            Badge.svelte
            Card.svelte
            MetricCard.svelte
            ThemeToggle.svelte
        /config
          app.ts
        /mocks
          pos.ts
        /stores
          session.svelte.ts
          theme.svelte.ts
        /types
          pos.ts
      /routes
        +layout.svelte
        +layout.ts
        +page.svelte
        /auth/login/+page.svelte
        /cashier/+page.svelte
        /admin/+page.svelte
        /superadmin/+page.svelte
    /static
      .htaccess
      robots.txt
  /backend
    .env.example
    composer.json
    /app
      /Enums
        UserRole.php
      /Http
        /Controllers/Api/V1
          AdminUserController.php
          AuthController.php
          DashboardController.php
          OrderController.php
          ProductController.php
          StoreController.php
        /Middleware
          EnsureUserHasRole.php
        /Requests
          StoreOrderRequest.php
          /Auth
            LoginRequest.php
      /Models
        Order.php
        OrderItem.php
        Product.php
        Store.php
        User.php
    /config
      auth.php
      cors.php
      sanctum.php
    /database
      /migrations
      /seeders
        DatabaseSeeder.php
    /routes
      api.php
      console.php
      web.php
```

## 5. Frontend Architecture

### 5.1 UI Strategy

- Mobile-first route: `/cashier`
- Desktop-first routes: `/admin`, `/superadmin`
- Shared shell:
  - sticky app header
  - dark mode toggle
  - shared design tokens
  - role-aware session badge

### 5.2 Design System

Implemented in `frontend/src/app.css`.

- Fonts:
  - `Manrope Variable` for body
  - `Space Grotesk Variable` for display headings
- Tokens:
  - spacing scale
  - semantic colors
  - surface elevation
  - radius and shadow values
- Theme support:
  - light and dark tokens under `:root` and `:root[data-theme='dark']`

### 5.3 Client State

- `theme.svelte.ts`
  - persists theme in `localStorage`
  - applies `data-theme` on `<html>`
- `session.svelte.ts`
  - stores a demo session for role-based UI walkthroughs
  - designed to be replaced by real Sanctum login responses
- `api/client.ts`
  - central bearer-token fetch helper
  - reads token from the same session storage contract

### 5.4 Routing

- `/`
  - product overview and architecture landing page
- `/auth/login`
  - prepared for `POST /api/v1/auth/login`
- `/cashier`
  - catalog discovery, cart review, payment CTA
- `/admin`
  - Tabs-based dashboard for catalog, reports, and operations
- `/superadmin`
  - Tabs-based dashboard for stores, admins, and settings posture

### 5.5 Static Hosting Strategy

SvelteKit uses:

- `adapter-static`
- `fallback: '200.html'`
- `static/.htaccess` rewrite for Apache-based SPA routing on Hostinger

This allows client-side routes to work after direct navigation on shared hosting.

## 6. Backend Architecture

### 6.1 API-Only Laravel

The Laravel app is configured as API-first:

- routes served from `routes/api.php`
- no Blade views in active use
- default frontend build tooling removed from the backend project
- CORS enabled for frontend-origin API requests

### 6.2 Auth and Authorization

- Sanctum bearer tokens
- `HasApiTokens` enabled on `User`
- login flow:
  1. SPA posts credentials to `/api/v1/auth/login`
  2. Laravel validates credentials
  3. Laravel returns `token`, `token_type`, and `user`
  4. SPA stores token and sends `Authorization: Bearer <token>`
- protected routes use `auth:sanctum`
- role enforcement uses `EnsureUserHasRole` middleware alias: `role`

### 6.3 Core Domain Models

#### `users`

- `name`
- `email`
- `phone`
- `role` (`cashier`, `admin`, `superadmin`)
- `is_active`
- `password`

#### `stores`

- `name`
- `code`
- `timezone`
- `currency`
- `address`
- `phone`
- `is_active`

#### `products`

- `store_id`
- `sku`
- `name`
- `slug`
- `category`
- `description`
- `price_cents`
- `stock_quantity`
- `is_active`

#### `orders`

- `order_number`
- `store_id`
- `cashier_id`
- `status`
- `payment_method`
- `subtotal_cents`
- `tax_cents`
- `discount_cents`
- `total_cents`
- `notes`
- `placed_at`
- `paid_at`

#### `order_items`

- `order_id`
- `product_id`
- `name_snapshot`
- `sku_snapshot`
- `quantity`
- `unit_price_cents`
- `line_total_cents`

### 6.4 API Surface

Public:

- `POST /api/v1/auth/login`
- `GET /api/v1/health`

Authenticated:

- `GET /api/v1/auth/me`
- `POST /api/v1/auth/logout`
- `GET /api/v1/products`
- `GET /api/v1/products/{product}`
- `GET /api/v1/orders`
- `GET /api/v1/orders/{order}`
- `POST /api/v1/orders`

Role-scoped:

- Cashier/Admin/Superadmin:
  - `GET /api/v1/cashier/overview`
- Admin/Superadmin:
  - `GET /api/v1/admin/overview`
  - `POST /api/v1/admin/products`
  - `PUT /api/v1/admin/products/{product}`
- Superadmin:
  - `GET /api/v1/superadmin/overview`
  - `GET|POST|PUT /api/v1/superadmin/stores...`
  - `GET|POST|PUT /api/v1/superadmin/admins...`

## 7. Local Development

### Frontend

```bash
cd frontend
cp .env.example .env
npm install
npm run dev
```

Expected variable:

```bash
PUBLIC_API_BASE_URL=http://127.0.0.1:8000/api/v1
```

### Backend

```bash
cd backend
cp .env.example .env
composer install
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

Recommended MySQL local database:

- database: `vanaila_pos`
- charset: `utf8mb4`
- collation: `utf8mb4_unicode_ci`

Testing still uses in-memory SQLite through `phpunit.xml` for faster CI/dev feedback.

## 8. Hostinger Deployment Notes

### Frontend

1. Run `npm run build` in `frontend/`
2. Upload contents of `frontend/build/` to `public_html/`
3. Keep `.htaccess` in the deployed root to support client routing

### Backend

1. Upload Laravel project to `api/`
2. Point the web entry to `api/public`
3. Configure `.env` with production MySQL credentials
4. Run:
   - `composer install --no-dev --optimize-autoloader`
   - `php artisan key:generate`
   - `php artisan migrate --force`
   - `php artisan config:cache`
   - `php artisan route:cache`

## 9. Recommended Next Work

1. Replace demo session login with the real `apiRequest()` call.
2. Add full CRUD screens for products, stores, and admin users.
3. Introduce report endpoints with date filters and export support.
4. Add inventory movement tables and payment reconciliation.
5. Add production logging, audit trails, and error monitoring.
