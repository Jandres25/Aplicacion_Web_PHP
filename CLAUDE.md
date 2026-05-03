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
```

Import the schema and seed data:

```bash
mysql -u root -p your_db < database/schema.sql
mysql -u root -p your_db < database/seeders.sql
```

There is no Composer autoloading — all `require_once` calls are explicit and manual.

## Architecture

This is a custom PHP MVC framework with no external dependencies (except bundled DomPDF in `libs/`).

**Request lifecycle:**

1. `public/index.php` — front controller; bootstraps and dispatches
2. `core/bootstrap.php` — loads env, DB, Flash, runs `AuthMiddleware` (redirects to `/login` if unauthenticated)
3. `routes/web.php` — registers all routes on the `Router`; all routes resolve to methods on `WebController`
4. `core/Router.php` — matches URI to handler; also serves static files from `public/`

**Layer responsibilities:**

- `app/Controllers/WebController.php` — the single HTTP-facing controller; delegates to domain controllers
- `app/Controllers/{Auth,Employee,Position,User}Controller.php` — domain controllers (no HTTP awareness; return result arrays)
- `app/Services/` — business logic; called by domain controllers
- `app/Repositories/` — PDO queries; called by services
- `app/Infrastructure/EmployeeFileStorage.php` — file upload handling (photos, CVs)
- `config/database.php` — PDO singleton (`Config\Database::getConnection()`)

**Rendering:**
`WebController::renderWithLayout()` wraps views with `app/Views/layout/{header,module_header,footer}.php`. Page metadata (title, breadcrumbs, icon) is passed via `pageHeaderData()` and `moduleBreadcrumbs()` helpers on `WebController`.

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
