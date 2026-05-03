# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Running the Application

- **Stack**: XAMPP (Apache + MySQL). The app runs at `http://localhost/Aplicacion_Web_PHP/public/`.
- **Start XAMPP**: `sudo /opt/lampp/lampp start`
- **Apache logs**: `/opt/lampp/logs/error_log`
- **PHP errors**: Check Apache error log or enable `display_errors` in `/opt/lampp/etc/php.ini`

## Setup

```bash
cp .env.example .env
# Edit .env with DB credentials and APP_URL
composer install
```

Import the schema and seed data:

```bash
mysql -u root -p your_db < database/schema.sql
mysql -u root -p your_db < database/seeders.sql
```

Autoloading is handled by Composer PSR-4. Run `composer dump-autoload` after adding new classes.

## Architecture

This is a custom PHP MVC framework using Composer PSR-4 autoloading (no manual `require_once`).

**Request lifecycle:**

1. `public/index.php` — front controller; loads `vendor/autoload.php`, sets `View::setBasePath()`, dispatches
2. `routes/web.php` — instantiates the 5 HTTP controllers and maps all routes
3. `core/Router.php` — matches URI to handler; also serves static files from `public/`

**Layer responsibilities:**

- `app/Http/Controllers/Controller.php` — abstract base; provides `renderWithLayout()`, `redirect()`, `requireLogin()`, `requireAdmin()`, CSRF helpers, and breadcrumb builders; injects `public_base`, `nombreUsuario`, and `flash` into every layout render
- `app/Http/Controllers/{Home,Auth,Employees,Positions,Users}Controller.php` — HTTP controllers; one per module
- `app/UseCases/{Auth,Employee,Position,User}UseCase.php` — domain logic; no HTTP awareness; return result arrays
- `app/Services/` — business logic; called by UseCases
- `app/Repositories/` — PDO queries; called by Services
- `app/Infrastructure/EmployeeFileStorage.php` — file upload handling (photos, CVs)
- `config/Database.php` — PDO singleton (`Config\Database::getConnection()`)

**Rendering:**
`Controller::renderWithLayout()` delegates to `Core\View::renderWithLayout()`, which wraps views with `resources/views/layout/{header,module_header,footer}.php`. Page metadata (title, breadcrumbs, icon) is built via `pageHeaderData()` and `moduleBreadcrumbs()` on the base `Controller`.

**Intelephense false positives:** Variables like `$public_base`, `$nombreUsuario`, `$flash`, `$csrfToken` in layout views will show as "undefined" in the IDE because they come from `extract($data)` inside `View::render()`. These are not real errors.

## CSRF Protection

- **Forms**: include a hidden `csrf_token` field; use `Security::getCsrfToken()` to generate it
- **AJAX**: read the token from the `<meta name="csrf-token">` tag in the layout and send it as `X-CSRF-Token` header; validate server-side with `Security::isValidCsrfToken()`
- All mutating routes validate CSRF before processing

## Frontend

- Bootstrap 5 + FontAwesome 6 (CDN)
- DataTables for listings
- SweetAlert2 for confirmations and toast notifications
- AJAX deletion pattern: JS in `public/js/{employees,positions,users}.js` calls POST endpoints and removes the table row on success without reloading

## Database

Tables: `tbl-puestos`, `tbl-empleados`, `tbl-usuarios` (note hyphen in table names — always quote them in SQL).

File uploads land in `public/storage/uploads/`. Default assets (`user-default.jpg`, `cv_default.pdf`) live there too.
