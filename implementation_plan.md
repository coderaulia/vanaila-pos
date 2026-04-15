# Backend System Build-Out

The Laravel backend has a solid foundation (auth, models, migrations, seeders, basic CRUD controllers). This plan fills the gaps identified in `project-status.md` and `architecture.md` to make it production-grade.

## User Review Required

> [!IMPORTANT]
> This plan focuses on backend hardening only. No frontend component changes. We will add Form Requests, Policies, API Resources, missing delete endpoints, report filters, stock deduction on order placement, and comprehensive feature tests.

> [!WARNING]
> Phase 4 adds stock deduction inside `OrderController@store`. This means placing an order will reduce `stock_quantity` on the related products. Currently stock is static.

## Proposed Changes

### Phase 1 — API Resources & Response Consistency

Wrap all JSON responses in Laravel API Resources for consistent, controlled output.

#### [NEW] `app/Http/Resources/UserResource.php`
#### [NEW] `app/Http/Resources/ProductResource.php`
#### [NEW] `app/Http/Resources/StoreResource.php`
#### [NEW] `app/Http/Resources/OrderResource.php`
#### [NEW] `app/Http/Resources/OrderItemResource.php`
#### [MODIFY] All 6 controllers — replace raw `response()->json()` with Resource classes

---

### Phase 2 — Form Requests & Validation

Extract inline validation from controllers into dedicated FormRequest classes.

#### [NEW] `app/Http/Requests/StoreProductRequest.php`
#### [NEW] `app/Http/Requests/UpdateProductRequest.php`
#### [NEW] `app/Http/Requests/StoreStoreRequest.php`
#### [NEW] `app/Http/Requests/UpdateStoreRequest.php`
#### [NEW] `app/Http/Requests/StoreAdminUserRequest.php`
#### [NEW] `app/Http/Requests/UpdateAdminUserRequest.php`
#### [MODIFY] `ProductController`, `StoreController`, `AdminUserController` — swap inline validation for FormRequests

---

### Phase 3 — Policies & Authorization

Add model policies so role checks happen at the resource level, not just route middleware.

#### [NEW] `app/Policies/ProductPolicy.php`
#### [NEW] `app/Policies/StorePolicy.php`
#### [NEW] `app/Policies/OrderPolicy.php`
#### [MODIFY] Controllers — add `$this->authorize()` calls

---

### Phase 4 — Business Logic Hardening

#### [MODIFY] `OrderController@store` — deduct `stock_quantity` on order placement inside the DB transaction
#### [NEW] `app/Http/Requests/UpdateOrderRequest.php` — for status updates (open → paid → cancelled)
#### [MODIFY] `OrderController` — add `update()` method for status transitions
#### [MODIFY] `routes/api.php` — add `PUT /api/v1/orders/{order}` route
#### [MODIFY] `routes/api.php` — add `DELETE` routes for products, stores, admins (soft-delete via `is_active = false`)

---

### Phase 5 — Reporting Endpoints

#### [NEW] `app/Http/Controllers/Api/V1/ReportController.php`
- `GET /api/v1/admin/reports/sales` — date-filtered sales summary
- `GET /api/v1/admin/reports/products` — top products by revenue
- `GET /api/v1/superadmin/reports/stores` — per-store revenue comparison
#### [MODIFY] `routes/api.php` — register report routes

---

### Phase 6 — Feature Tests

#### [NEW] `tests/Feature/Products/ProductCrudTest.php`
#### [NEW] `tests/Feature/Stores/StoreCrudTest.php`
#### [NEW] `tests/Feature/AdminUsers/AdminUserCrudTest.php`
#### [NEW] `tests/Feature/Reports/SalesReportTest.php`
#### [NEW] `tests/Feature/Orders/UpdateOrderTest.php`
#### [MODIFY] Existing `LoginTest.php`, `StoreOrderTest.php` — expand edge cases

---

### Phase 7 — Documentation & Project Files

#### [NEW] `commit-logs.md` — short progress log (~80 words)
#### [MODIFY] `project-status.md` — updated to reflect completed backend work
#### [MODIFY] `architecture.md` — add new endpoints, policies, and resource classes

## Verification Plan

### Automated Tests
```bash
cd backend
php artisan test
php artisan route:list --path=api
```

### Manual Verification
- Run `php artisan migrate:fresh --seed` to confirm clean state
- Test login → create order → check stock deduction flow via curl or frontend

## Open Questions

> [!IMPORTANT]
> Should product/store/admin deletes be **hard deletes** or **soft-deactivation** (setting `is_active = false`)? The plan currently uses soft-deactivation to preserve data integrity for order history.
