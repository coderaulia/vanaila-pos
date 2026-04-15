# Project Status

Date: 2026-04-15

## Current State

Frontend is production-shaped. Backend is now hardened through Phase 4: full Policies, API Resources, FormRequests, stock deduction, order status transitions, and soft-delete deactivation routes. 25 routes registered, 4 tests passing.

## Completed

- Static SvelteKit frontend with role-scoped shells and real Sanctum login
- Laravel 11 API: auth, 6 controllers, 5 models, API Resources, FormRequests
- Policies on Order/Product/Store (Phase 3)
- Stock deduction with race-condition guard (Phase 4)
- Order status transitions: open → paid / cancelled (Phase 4)
- Soft-delete deactivation for products, stores, admins (Phase 4)

## Next Up

1. Report endpoints with date filters (Phase 5)
2. Expanded feature test coverage (Phase 6)
3. Documentation update (Phase 7)
