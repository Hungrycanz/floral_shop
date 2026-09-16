# Session Notes — Floral Shop (2026-09-16)

## What this session covered

### 1. Flaw audit
Ran a full review of the Laravel floral-shop app. Key findings were grouped by severity and covered:
- **Critical:** order-status vocabulary mismatch, `User.role` mass assignability, unauth'd API order leak, payment self-marking bypass.
- **High:** price-tampering addons, review authorization gaps, courier/zone validation, missing status transitions, duplicate payment guards, inactive-product ordering, raw API Resources.
- **Medium/Low:** no pagination, missing FK indexes, broken `Order::reviews()` relationship, missing factory traits / return types, unused `OrderStatus` enum, mixed model conventions.

### 2. Top-priority fixes (all applied + regression tests added)
1. **Status vocabulary** — New migration `2026_09_16_124016_align_order_statuses_with_enum.php` widens the Postgres `orders.status` CHECK to `[placed, confirmed, out_for_pickup, out_for_delivery, delivered, cancelled]` and sets the default to `placed`. Migration is `pgsql`-aware (returns early on SQLite, which tests use). `OrderPlacementService::placeOrder` now writes `OrderStatus::Placed->value`; `UpdateOrderStatusRequest` uses `Rule::enum(OrderStatus::class)`; courier filters/validations use the enum.
2. **Role escalation** — Removed `role` from `User::$fillable`. Added `admin()`, `courier()`, `customer()` factory states (via `afterCreating` + `forceFill`). Updated `DatabaseSeeder` and `RoleAndDeliveryTest` to use states instead of passing `role` to `create()`.
3. **API PII leak** — `GET /api/orders/{order}` now has `['web', 'auth']` middleware + owner-only guard (`abort_unless($order->user_id === $request->user()->id, 403)`). Tests: 401 unauthenticated, 403 cross-user, 200 owner.
4. **Payment bypass** — `PaymentController::process` is **admin-only** now (was owner-or-admin). Added orphaned-payment guard. Test updated: customer → 403, admin → 200.
5. **Request authorization** — `StoreProductRequest::authorize()` and `UpdateOrderStatusRequest::authorize()` now return `$this->user()?->isAdmin() ?? false` (were `return true`). `Admin/OrderController::updateStatus` uses the shared Form Request.

### 3. Frontend theming fix
App was mixing the **rose theme** (shop layout, `components/layouts/shop.blade.php`) with **default Breeze gray** in three places. Converted all to the rose theme:
- `layouts/guest.blade.php` — rose gradient background + "Bloom & Petal" branding (affects /login, /register, forgot/reset password, verification).
- `profile/edit.blade.php` — switched from `<x-app-layout>` to `<x-layouts.shop>`.
- `dashboard.blade.php` — now a rose card-style account page (`<x-layouts.shop>`).
- `auth/login.blade.php` — added "Don't have an account? Register" link; `auth/register.blade.php` link restyled to rose (was indigo/gray).

The CSS itself was verified healthy (all rose utilities + hover variants present in the Vite build). Root cause was layout mismatch, not a CSS build failure.

## Need to remember
- App runs via `concurrently`: `php artisan serve` (localhost:8000) + `npm run dev` (Vite on `[::1]:5173`) + queue worker.
- `APP_URL=http://localhost:8000`; `public/hot` points Vite at `http://[::1]:5173` (IPv6).
- **DB is PostgreSQL** for the app, but **tests run on SQLite** (`RefreshDatabase`). Any new migration with PG-specific SQL must guard on `DB::getDriverName() !== 'pgsql'`.
- `@tailwindcss/vite@4.3.3` (Tailwind v4 plugin) is installed but unused — project is **Tailwind v3** (`@tailwind` directives + `postcss.config.js` + `tailwind.config.js`). Flagging: it's a version mismatch worth cleaning up, but it's NOT currently wired into `vite.config.js`.
- Architecture: server-rendered Blade, `App\Services\CartService` (session cart), `OrderPlacementService`, `PaymentGatewayService` (stub gateway). Three roles: customer / admin / courier via `role` column + `EnsureUserHasRole` (`role:` alias).

## Still open (from audit — not yet fixed)
- Price-tampering: `OrderPlacementService::resolveAddons()` trusts client-supplied addon prices.
- Reviews: no purchase verification + `ReviewController` update path has no ownership check.
- No status *transition* validation (admin/courier can jump statuses, e.g. placed → delivered).
- No pagination on product listings / courier deliveries.
- Missing FK indexes (`products.category_id`, `order_items.product_id`, `orders.courier_id`, `orders.delivery_zone_id`, `payments.order_id`).
- `Order::reviews()` relationship broken (wrong `hasManyThrough` key).
- Missing DB CHECK constraints (price >= 0, stock >= 0, rating 1–5).
- No factories for most models; `OrderStatus` enum still duplicated by `OrderTrackingEvent` constants.
- `$timestamps = false` on Product/Order/Payment/OrderItem but some tables have created_at.