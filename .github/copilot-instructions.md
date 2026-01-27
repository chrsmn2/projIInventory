**Big Picture**

- **Type:** Laravel 12 PHP web application with a Vite/Tailwind frontend.
- **Structure:** backend logic in `app/` (controllers under `app/Http/Controllers`, requests in `app/Http/Requests`, models in `app/`), routes in `routes/`, views in `resources/views`, migrations in `database/migrations` and seeders in `database/seeders`.
- **Auth / Roles:** Role-based routing: routes use middleware `role:admin` and `role:supervisor` (see [routes/web.php](routes/web.php)). Admin and Supervisor areas are separated by prefixes and controller namespaces.

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

**Key files & where to look**

- Routes and high-level flows: [routes/web.php](routes/web.php)
- Example admin controller: [app/Http/Controllers/Admin/ItemController.php](app/Http/Controllers/Admin/ItemController.php)
- Service providers and app bootstrapping: [app/Providers/AppServiceProvider.php](app/Providers/AppServiceProvider.php)
- Composer scripts + PHP requirements: [composer.json](composer.json)
- Frontend build: [package.json](package.json) and [vite.config.js](vite.config.js)

**Editing guidance & quick examples**

- When adding a new admin area: create a controller under `app/Http/Controllers/Admin`, add resource routes in `routes/web.php` inside the `admin` middleware group and name prefix `admin.`.
- For column changes in migrations on an existing table, `doctrine/dbal` is available — create a migration and use `Schema::table` with `->change()`.
- To run a single test file: `php artisan test --filter 'MyTest'` or use `vendor/bin/phpunit tests/Feature/MyTest.php`.

**Testing & code style**

- Tests: `composer run test` runs `php artisan test` after clearing config cache; PHPUnit 11 is configured.
- Code style: Laravel Pint is available in dev dependencies; run it via `vendor/bin/pint` or `composer exec -- vendor/bin/pint` if needed.

**Integrations & external dependencies**

- Backend: Laravel framework, `doctrine/dbal` for migrations, `laravel/breeze` present in dev for auth scaffolding.
- Frontend: Vite, Tailwind, Alpine.js, `laravel-vite-plugin` — use `npm run dev` for hot reloads.
- Background processing: repo expects queue worker in dev workflows (`php artisan queue:listen` is used by the `dev` script).

**When changing DB or migrations**

- Prefer creating a new migration (timestamped files under `database/migrations`). If altering existing columns use `doctrine/dbal` which is already required.
- If you need a temporary sqlite DB for tests, note composer scripts create `database/database.sqlite` during `post-create-project-cmd`.

**If you're an AI agent — actionable checklist**

- 1) Read [routes/web.php](routes/web.php) to understand route groups and role separation.
- 2) Open the relevant controller under `app/Http/Controllers/<Admin|Supervisor>` and corresponding request classes in `app/Http/Requests` for validation patterns.
- 3) Use `composer run setup` or run the smaller sequence (`composer install`, copy `.env`, `php artisan key:generate`, `php artisan migrate`) before running the app.
- 4) For frontend changes use `npm run dev` and observe Vite output; `composer run dev` runs the full stack concurrently.

**Questions / gaps to confirm**

- Do you prefer models in `app/` to stay there or be moved to `app/Models/`? Search-and-move must update namespaces.
- Confirm which DB is used in CI / production (MySQL or sqlite) so migrations and seeds are validated against the target.

If anything here is unclear or you'd like me to expand examples (e.g., a sample migration edit, controller refactor, or tests), tell me which area to flesh out.
