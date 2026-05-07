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

1. `public/index.php` — front controller; loads `vendor/autoload.php`, builds `Core\Container`, registers bindings from `config/container.php`, dispatches
2. `routes/web.php` — resolves all 5 HTTP controllers via `$container->resolve(XController::class)` and maps all routes
3. `core/Router.php` — matches URI to handler; also serves static files from `public/`

**Layer responsibilities:**

| Layer | Path | Role |
|---|---|---|
| HTTP | `app/Http/Controllers/` | One controller per module; validates CSRF, builds Request DTOs, calls UseCases, renders views |
| HTTP | `app/Http/Requests/` | Typed DTOs built from `$_POST`; `fromArray()` + `validate()` — `$_POST` never crosses this boundary |
| Application | `app/UseCases/` | Orchestrates domain logic; receives typed Requests, returns `OperationResult`; no HTTP awareness |
| Application | `app/UseCases/DTOs/` | `OperationResult` — typed result replacing `['success' => bool, 'message' => string]` arrays |
| Domain | `app/Domain/Models/` | POPOs (`Employee`, `Position`, `User`); `fromRow(array): self` + `toArray(): array` |
| Domain | `app/Domain/Contracts/` | Repository interfaces (`EmployeeRepositoryInterface`, etc.) |
| Domain | `app/Services/` | Business logic; depends on repository interfaces, not concrete classes |
| Infrastructure | `app/Repositories/` | PDO queries; implement the repository contracts |
| Infrastructure | `app/Infrastructure/` | `EmployeeFileStorage` — file upload and deletion |
| Infrastructure | `config/Database.php` | PDO singleton (`Config\Database::getConnection()`) |
| Cross-cutting | `core/Container.php` | Lightweight DI container; resolves dependencies via `ReflectionClass`; used only in bootstrap |
| Cross-cutting | `core/` | `Router`, `View`, `Flash`, `Security`, `Env`, `ErrorPage` |
| Cross-cutting | `app/Middleware/AuthMiddleware.php` | Checks `$_SESSION['logueado']`, falls back to `AuthUseCase::handleRememberLogin()` |

**DI Container:**
`config/container.php` registers bindings: `PDO` as singleton via closure, and the three repository interface → concrete class mappings. The Container is only used in bootstrap (`public/index.php` and `routes/web.php`) — never injected into domain or application classes.

**Request DTOs pattern:**
```
Controller POST method:
  1. hasValidCsrfToken($_POST)        — CSRF check
  2. XxxRequest::fromArray($_POST)    — build typed DTO
  3. $req->validate()                 — validate; Flash + redirect on errors
  4. $useCase->action($req)           — pass DTO to UseCase
  5. check OperationResult->success   — render or redirect
```

**Rendering:**
`Controller::renderWithLayout()` delegates to `Core\View::renderWithLayout()`, which wraps views with `resources/views/layout/{header,module_header,footer}.php`. Page metadata (title, breadcrumbs, icon) is built via `pageHeaderData()` and `moduleBreadcrumbs()` on the base `Controller`.

**Intelephense false positives:** Variables like `$public_base`, `$nombreUsuario`, `$flash`, `$csrfToken` in layout views will show as "undefined" in the IDE because they come from `extract($data)` inside `View::render()`. These are not real errors.

## Remember Me (cookie persistence)

- Enabled by default; controlled by `REMEMBER_ME_ENABLED=true` in `.env`
- Cookie lifetime: `REMEMBER_ME_LIFETIME=30` (days)
- **Cookie format**: `{userId}:{plainToken}` — the plain token is never stored; only its `sha256` hash goes into the DB column `remember_token` on `tbl-usuarios`
- **Rotation**: every successful cookie-based login issues a new token and overwrites the old one (token rotation)
- **Logout**: `AuthUseCase::handleLogout()` revokes the DB token and expires the cookie; `AuthController` calls this via `$this->authUseCase->handleLogout()`
- `Security::setRememberCookie()` / `clearRememberCookie()` / `getRememberCookie()` in `core/Security.php` manage the raw cookie with `HttpOnly`, `SameSite=Lax`, and `Secure` (auto-detected)
- Schema columns added to `tbl-usuarios`: `remember_token VARCHAR(64) NULL`, `remember_token_expires DATETIME NULL`

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

Tables: `tbl-puestos`, `tbl-empleados`, `tbl-usuarios` (note hyphen in table names — always quote them in SQL). `tbl-usuarios` has two extra nullable columns for remember-me: `remember_token` and `remember_token_expires`.

File uploads land in `public/storage/uploads/`. Default assets (`user-default.jpg`, `cv_default.pdf`) live there too.
