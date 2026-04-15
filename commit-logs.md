# Commit Logs

## 2026-04-15

**`f52a6e1` — fix: UI finalized from login, admin, superadmin and table design**
Stripped all boilerplate PageIntro hero sections from every route. Downsized display-title and section-title clamp values for mobile-first density. Replaced the landing page with a redirect to `/auth/login`. Cleaned up the login page into a focused sign-in card with real API submission. Removed unused imports, fixed lint errors, and ran format. 26 files changed across the frontend — no backend modifications in this pass.

---

**`[pending]` — feat(backend): Phase 3 & 4 — Policies, stock deduction, order transitions, soft-deletes**
Added `OrderPolicy@update` so cashiers can only mutate their own orders. Stock is now deducted atomically via `lockForUpdate` on order creation; insufficient stock throws a 422. Cancelled orders restore stock automatically. Added `UpdateOrderRequest` and `OrderController@update` for `open→paid/cancelled` transitions. Added `destroy()` soft-deactivation (sets `is_active=false`) to products, stores, and admins — superadmin guard included. Registered `DELETE` routes and `PUT /orders/{order}`. All 4 tests pass.
