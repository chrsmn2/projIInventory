**Big Picture**

- **Type:** Laravel 12 PHP web application with a Vite/Tailwind frontend.
- **Structure:** backend logic in `app/` (controllers under `app/Http/Controllers`, requests in `app/Http/Requests`, models in `app/`), routes in `routes/`, views in `resources/views`, migrations in `database/migrations` and seeders in `database/seeders`. Export classes are in `app/Exports/`.
- **Auth / Roles:** Role-based routing: routes use middleware `role:admin` and `role:supervisor` (see [routes/web.php](routes/web.php)). Admin and Supervisor areas are separated by prefixes and controller namespaces.
- **Database Models:** Core models live in `app/` (not `app/Models/`): `Item`, `Category`, `Unit`, `IncomingItem`, `IncomingItemDetail`, `OutgoingItem`, `OutgoingItemDetail`, `Loan`, `LoanApproval`, `Supplier`, `Requester`, `User`, `Departement`. Each has relationships defined via Eloquent.

**Dev workflows (commands you can run)**

- Install / bootstrap (composer script):

  - `composer install`
  - `npm install`
  - `composer run-script setup` runs the repo's convenience setup (copies `.env`, generates key, runs migrations, builds assets).

- Local development (two common options):

  - Lightweight: `php artisan serve` + `npm run dev` (Vite) + `php artisan queue:listen` if needed.
  - Full parallel dev: `composer run dev` — runs server, queue listener, pail logger, and Vite concurrently (see `package.json` scripts).

- Build for production: `npm run build` then deploy as usual; server side still requires `composer install --no-dev` and publishing assets.

- Database: use `php artisan migrate` / `php artisan migrate:fresh --seed` for reset-and-seed workflows. The project includes `doctrine/dbal` for column changes.

- Useful housekeeping commands seen in this repo: `php artisan config:clear`, `php artisan view:clear`, `php artisan storage:link` (when using uploaded files).

**Project-specific conventions & patterns**

- Controllers use resourceful routes; prefer updating controller actions when routes are `Route::resource('items', ItemController::class)` (see [routes/web.php](routes/web.php)).
- Validation typically uses Form Request classes in `app/Http/Requests`; prefer adding/using them over inline validation for consistency.
- Many Eloquent models live directly under `app/` (e.g., `app/Item.php`, `app/Category.php`) instead of `app/Models/` — autoloading uses `App\\` → `app/`.
- Role checks are centralized in middleware named `role` — when adding new role logic, locate middleware under `app/Http/Middleware`.
- Frontend assets use Vite + Tailwind; JS lives under `resources/js` and CSS under `resources/css` (see `vite.config.js`, `tailwind.config.js`).
- **Layout consistency:** Admin views follow a standard pattern: page header with title + search/filter section (white box with form) + data table (white box with hover effect) + pagination. See [resources/views/admin/items/index.blade.php](resources/views/admin/items/index.blade.php) and [resources/views/admin/incoming/index.blade.php](resources/views/admin/incoming/index.blade.php) for examples.
- **Report system:** Reports in `app/Http/Controllers/Admin/ReportController.php` handle stock analysis, movement tracking (incoming/outgoing), and loans. Reports support Excel export via classes in `app/Exports/` (e.g., `StockReportExport`, `MovementReportExport`).
- **Excel exports:** Use `Maatwebsite\Excel\Facades\Excel` with export classes implementing `FromCollection`, `WithHeadings`, `WithStyles`, `ShouldAutoSize`. Export classes handle styling (borders, colors, alignment) and data transformation.

**Key files & where to look**

- Routes and high-level flows: [routes/web.php](routes/web.php)
- Reports and export logic: [app/Http/Controllers/Admin/ReportController.php](app/Http/Controllers/Admin/ReportController.php)
- Excel export classes: [app/Exports/StockReportExport.php](app/Exports/StockReportExport.php), [app/Exports/MovementReportExport.php](app/Exports/MovementReportExport.php)
- Report views: [resources/views/admin/reports/stock.blade.php](resources/views/admin/reports/stock.blade.php), [resources/views/admin/reports/movement.blade.php](resources/views/admin/reports/movement.blade.php)
- Example admin controller: [app/Http/Controllers/Admin/ItemController.php](app/Http/Controllers/Admin/ItemController.php)
- Service providers and app bootstrapping: [app/Providers/AppServiceProvider.php](app/Providers/AppServiceProvider.php)
- Composer scripts + PHP requirements: [composer.json](composer.json)
- Frontend build: [package.json](package.json) and [vite.config.js](vite.config.js)

**Editing guidance & quick examples**

- When adding a new admin area: create a controller under `app/Http/Controllers/Admin`, add resource routes in `routes/web.php` inside the `admin` middleware group and name prefix `admin.`.
- For column changes in migrations on an existing table, `doctrine/dbal` is available — create a migration and use `Schema::table` with `->change()`.
- To add Excel export to a new report: create an export class in `app/Exports/` extending `FromCollection, WithHeadings, WithStyles, ShouldAutoSize`, then use `Excel::download(new YourExport($data), 'filename.xlsx')` in controller.
- To run a single test file: `php artisan test --filter 'MyTest'` or use `vendor/bin/phpunit tests/Feature/MyTest.php`.
- Report views always include: statistics cards (grid of key metrics), filter form with search/dropdowns/date ranges, data table with hover effect, and pagination. Use standard filter pattern: search input, category/type selects, month/year pickers, per-page dropdown, Filter/Reset/Export action buttons.

**Testing & code style**

- Tests: `composer run test` runs `php artisan test` after clearing config cache; PHPUnit 11 is configured.
- Code style: Laravel Pint is available in dev dependencies; run it via `vendor/bin/pint` or `composer exec -- vendor/bin/pint` if needed.

**Integrations & external dependencies**

- Backend: Laravel framework, `doctrine/dbal` for migrations, `laravel/breeze` present in dev for auth scaffolding, `maatwebsite/excel` for Excel import/export.
- Frontend: Vite, Tailwind, Alpine.js, `laravel-vite-plugin` — use `npm run dev` for hot reloads.
- Background processing: repo expects queue worker in dev workflows (`php artisan queue:listen` is used by the `dev` script).

**When changing DB or migrations**

- Prefer creating a new migration (timestamped files under `database/migrations`). If altering existing columns use `doctrine/dbal` which is already required.
- If you need a temporary sqlite DB for tests, note composer scripts create `database/database.sqlite` during `post-create-project-cmd`.

**Report & Analytics patterns**

- **Stock Report:** filters by search, category, report_type (all/low/out_of_stock/damaged), condition (good/damaged). Stats auto-calculate from all items. Exports to Excel with formatted headers/styling.
- **Movement Report:** filters by type (incoming/outgoing), month, year. Shows transactions with admin/supplier or requester info. Exports detail to Excel.
- **Loan Report:** filters by status, shows loans ordered by date desc.
- Statistics always load full dataset for accurate context (not filtered dataset).

**If you're an AI agent — actionable checklist**

- 1) Read [routes/web.php](routes/web.php) to understand route groups and role separation.
- 2) Open the relevant controller under `app/Http/Controllers/Admin` or `app/Http/Controllers/Supervisor` and check corresponding request classes in `app/Http/Requests`.
- 3) For reports: check [app/Http/Controllers/Admin/ReportController.php](app/Http/Controllers/Admin/ReportController.php) for query logic and [app/Exports/](app/Exports/) for data transformation.
- 4) View views: all admin views follow consistent pattern in [resources/views/admin/](resources/views/admin/) - use existing files as templates.
- 5) Use `composer run setup` or the sequence: `composer install`, copy `.env`, `php artisan key:generate`, `php artisan migrate` before running.
- 6) For frontend changes use `npm run dev` and observe Vite output; `composer run dev` runs the full stack concurrently.

**Questions / gaps to confirm**

- Do you prefer models in `app/` to stay there or be moved to `app/Models/`? Search-and-move must update namespaces.
- Confirm which DB is used in CI / production (MySQL or sqlite) so migrations and seeds are validated against the target.

If anything here is unclear or you'd like me to expand examples (e.g., a sample migration edit, export class, or report view), tell me which area to flesh out.
