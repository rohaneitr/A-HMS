# 🧠 HMS SaaS — Project Memory & Architecture Guide

This document serves as the central reference (Project Memory) for the HMS SaaS Medical/Hospital application codebase, architecture, database schemas, security audit findings, and deployment playbooks.

---

## 1. ⚙️ Project Technology Stack & Architecture

### Backend Engine
- **Framework:** CodeIgniter 3.x (MVC Architecture) extended with **HMVC (Hierarchical Model-View-Controller)**.
- **PHP Compatibility:** Optimized and tested for PHP 8.1-Apache (with support up to PHP 8.3).
- **Authentication:** CodeIgniter Ion Auth Library (v2.5.2).

### Database Engine
- **Database:** MariaDB 10.6+ / MySQL 8.x.
- **Session Handler:** Database-driven sessions via the `ci_sessions` InnoDB table.

### Frontend Layer
- **Rendering:** Server-Side Rendered (SSR) HTML views via PHP controllers.
- **UI Frameworks:** AdminLTE 3 Dashboard, Bootstrap 4 CSS framework, jQuery, and DataTables jQuery Plugin.
- **Charts & Analytics:** Chart.js, Morris.js, Flot Charts, Sparkline.
- **Custom Theme:** `common/css/custom-style.css` — loaded last in `dashboard.php`. Safe CSS-only overrides. No PHP/JS touched.

---

## 2. 📁 Key Directory & Files Map

- **[Multi-Hospital/](file:///c:/Users/Rohan/Desktop/hmssaas/Multi-Hospital/)** — Core application codebase.
  - **[application/config/](file:///c:/Users/Rohan/Desktop/hmssaas/Multi-Hospital/application/config/)** — CodeIgniter config files.
    - [config.php](file:///c:/Users/Rohan/Desktop/hmssaas/Multi-Hospital/application/config/config.php): Session, Cookie, and CSRF configuration.
    - [database.php](file:///c:/Users/Rohan/Desktop/hmssaas/Multi-Hospital/application/config/database.php): Database profiles.
    - [hooks.php](file:///c:/Users/Rohan/Desktop/hmssaas/Multi-Hospital/application/config/hooks.php): Hook registration (defines `pre_controller` hooks).
    - [ion_auth.php](file:///c:/Users/Rohan/Desktop/hmssaas/Multi-Hospital/application/config/ion_auth.php): Ion Auth specific rules (bcrypt rounds, lockout parameters).
  - **[application/hooks/](file:///c:/Users/Rohan/Desktop/hmssaas/Multi-Hospital/application/hooks/)** — Runtime hook handlers.
    - [required.php](file:///c:/Users/Rohan/Desktop/hmssaas/Multi-Hospital/application/hooks/required.php): Crucial middleware. Handles API bypasses, session initialization, and global auth checks.
  - **[application/modules/](file:///c:/Users/Rohan/Desktop/hmssaas/Multi-Hospital/application/modules/)** — HMVC Modules.
    - **[auth/](file:///c:/Users/Rohan/Desktop/hmssaas/Multi-Hospital/application/modules/auth/)** — Authentication controller and login/logout views.
    - **[home/](file:///c:/Users/Rohan/Desktop/hmssaas/Multi-Hospital/application/modules/home/)** — Home / Dashboard controller and global layout views (header, footer, sidebar).
    - **[superadmin/](file:///c:/Users/Rohan/Desktop/hmssaas/Multi-Hospital/application/modules/superadmin/)** — Super Administrator actions.
    - **[pgateway/](file:///c:/Users/Rohan/Desktop/hmssaas/Multi-Hospital/application/modules/pgateway/)** — Payment Gateway management (list, settings form).
    - **[sslcommerz/](file:///c:/Users/Rohan/Desktop/hmssaas/Multi-Hospital/application/modules/sslcommerz/)** — SSLCOMMERZ payment processing (initiate, success, fail, cancel, IPN).
  - **[common/css/custom-style.css](file:///c:/Users/Rohan/Desktop/hmssaas/Multi-Hospital/common/css/custom-style.css)** — ⭐ MASTER UI OVERRIDE FILE. Purple + Dark Slate theme with Inter font.
  - **[adminlte/dist/css/changes.css](file:///c:/Users/Rohan/Desktop/hmssaas/Multi-Hospital/adminlte/dist/css/changes.css)** — AdminLTE-specific tweaks (do not modify for theme changes).
- **[Database/](file:///c:/Users/Rohan/Desktop/hmssaas/Database/)** — Initial database scripts.
- **[Dockerfile](file:///c:/Users/Rohan/Desktop/hmssaas/Dockerfile)** — Builds the custom `php:8.1-apache` container.
- **[docker-compose.yml](file:///c:/Users/Rohan/Desktop/hmssaas/docker-compose.yml)** — Local environment orchestration file.
- **[application/migrations/update_branding_2026.sql](file:///c:/Users/Rohan/Desktop/hmssaas/Multi-Hospital/application/migrations/update_branding_2026.sql)** — DB migration for branding + SSLCOMMERZ seeding.
- **[run-migration.php](file:///c:/Users/Rohan/Desktop/hmssaas/Multi-Hospital/run-migration.php)** — One-time migration runner. **DELETE after use!**

---

## 3. 🛡️ Security Architecture & Hardening Manifest

The codebase has been audited and secured against major OWASP vulnerabilities. Below are the key controls implemented:

### A. Session Hardening & Bleed Mitigation (`SESS-001` & `SESS-002`)
- **Vulnerability:** File-based sessions stored in the shared `/tmp` directory resulted in concurrent request privilege collisions and session bleed across users.
- **Fix:** Switched session handling to **database-backed sessions** (`sess_driver = 'database'`).
- **Rotation:** `sess_regenerate_destroy` set to `TRUE`. The old database session row is atomically destroyed on ID rotation or privileges upgrade.
- **Logout Sequence:** Hardened in [Ion_auth.php](file:///c:/Users/Rohan/Desktop/hmssaas/Multi-Hospital/application/libraries/Ion_auth.php) to delete persistent cookies, run `sess_destroy()`, and trigger native PHP session regeneration (`session_regenerate_id(TRUE)`).

### B. Session Fixation Mitigation (`AUTH-001`)
- **Vulnerability:** The pre-authentication session ID survived into the authenticated dashboard context.
- **Fix:** Added `$this->session->sess_regenerate(TRUE)` inside the login success check in [Auth.php](file:///c:/Users/Rohan/Desktop/hmssaas/Multi-Hospital/application/modules/auth/controllers/Auth.php) to immediately issue a new session ID and discard the old one.

### C. Constructor Authorization Guards (`PRIV-001` & `PRIV-002`)
- **Vulnerability:** CodeIgniter `redirect()` calls send headers but do not terminate PHP execution. Lack of `exit()` allowed unauthorized users to proceed through controller code execution.
- **Fix:** Implemented strict check guards inside the constructors of [Home.php](file:///c:/Users/Rohan/Desktop/hmssaas/Multi-Hospital/application/modules/home/controllers/Home.php) and [Superadmin.php](file:///c:/Users/Rohan/Desktop/hmssaas/Multi-Hospital/application/modules/superadmin/controllers/Superadmin.php) followed by an explicit `exit()`.

### D. Universal CSRF Injection Engine (`CSRF-001`)
- **Fix:** CSRF protection enabled globally in [config.php](file:///c:/Users/Rohan/Desktop/hmssaas/Multi-Hospital/application/config/config.php).
- **Mechanism:** A centralized script block inside [footer.php](file:///c:/Users/Rohan/Desktop/hmssaas/Multi-Hospital/application/modules/home/views/footer.php) intercepts both **jQuery AJAX** (`$.ajaxSetup`) and **Axios** requests, dynamically injecting the CSRF token into all outgoing POST payloads. It also listens for responses to handle hot-rotation of tokens.

### E. TLS-Sensitive Secure Cookies (`COOK-001`)
- **Fix:** `cookie_httponly` set to `TRUE` (unconditional).
- **TLS Context Detector:** Rather than hardcoding `cookie_secure`, [config.php](file:///c:/Users/Rohan/Desktop/hmssaas/Multi-Hospital/application/config/config.php) evaluates direct HTTPS flags, proxy headers (`X-Forwarded-Proto`), and the `CI_ENV` environment variable to dynamically activate `cookie_secure` in production while remaining compatible with plain HTTP on local development.

---

## 4. 🗄️ Database Session Storage Scheme

CodeIgniter requires the `ci_sessions` table to exist when using the `database` session driver. **This table must be built with the InnoDB storage engine** to support atomic row-level locking.

```sql
CREATE TABLE IF NOT EXISTS `ci_sessions` (
    `id`         varchar(128)         NOT NULL,
    `ip_address` varchar(45)          NOT NULL,
    `timestamp`  int(10) unsigned     DEFAULT 0 NOT NULL,
    `data`       blob                 NOT NULL,
    PRIMARY KEY (`id`),
    KEY `ci_sessions_timestamp` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

---

## 5. 🛠️ Development & Pentesting Utility Scripts

The workspace contains several pre-configured utilities to assist with testing and administration:
- [run_local.sh](file:///c:/Users/Rohan/Desktop/hmssaas/run_local.sh): Deploy stack locally, wait for DB, and seed the `ci_sessions` table automatically.
- [reset_superadmin.php](file:///c:/Users/Rohan/Desktop/hmssaas/reset_superadmin.php): Resets the superadmin password to `Admin@HMS2024!` with cost factor 14.
- [check_creds.php](file:///c:/Users/Rohan/Desktop/hmssaas/check_creds.php): Utility to verify password strength/validity of default accounts.
- [check_superadmin.php](file:///c:/Users/Rohan/Desktop/hmssaas/check_superadmin.php): Retrieves superadmin hash info and checks against common candidate dictionaries.

---

## 🔒 Strict Guardrails & Development Rules

1. **Do Not Touch Core IDs/Classes:** The application is heavily driven by legacy jQuery selectors. Under no circumstances should you edit or rename existing HTML `id` attributes or core JavaScript class hooks.
2. **Override Styles Safely:** All UI customization MUST go into `common/css/custom-style.css` only. Never edit `adminlte.min.css`, `bootstrap.min.css`, or any plugin CSS.
3. **No Core Frame Modifications:** Never modify files in `vendor/` or third-party core CodeIgniter files. Put overrides inside modular extension controllers (`MX_Controller`).
4. **PHP 8.3 Guidelines:** Maintain strict typing, avoid deprecated functions, and ensure type-safety to prevent fatal engine crashes in high-tier execution environments.
5. **Payment Gateway Column Mapping:** The `paymentGateway` table uses `APIUsername` = Store ID / Username, `APIPassword` = Store Password / API Key. SSLCOMMERZ uses these same columns.

---

## 6. 🎨 Branding & Customization Blueprint

### Custom Identity Profile (Fast Technologies)
- **App Name:** A+HMS (displayed on page titles, login view, and dashboard navigation headers).
- **Vendor / Company:** Fast Technologies (used as system vendor and copyright footprint).
- **Contact Info:** Email: `fctbd1@gmail.com` | Phone: `+8801759190782`.
- **Logo Assets:**
  - Primary App Logo: `Multi-Hospital/uploads/logo.png`.
  - Browser Favicon: `Multi-Hospital/uploads/favicon.png`.

### UI Theme
- **Theme Name:** Purple + Dark Slate (Premium Luxury)
- **Primary Color:** `#7c3aed` (Vibrant Purple)
- **Sidebar BG:** `#1e1b4b` (Deep Indigo) → `#13103a` gradient
- **Font:** Inter (Google Fonts, CSS import)
- **Override File:** `common/css/custom-style.css` — loaded last via `dashboard.php` line 111
- **Safe for changes:** Only edit this file for color/design updates

### Language Customization (Bengali Support)
- **Translation Directory:** `Multi-Hospital/application/language/bangla/` contains modified language translation scripts (e.g. `system_syntax_lang.php`).
- **Database Activation:**
  Activated in the `language` table with entry:
  `INSERT INTO language (id, language, folder_name, flag_icon, description, status) VALUES (17, 'bangla', 'bangla', 'bd', 'বাংলা (Bangla)', '1');`
- **User Selector:**
  - Added to the dropdown flag selector in [login.php](file:///c:/Users/Rohan/Desktop/hmssaas/Multi-Hospital/application/views/auth/login.php) mapping to code `bd`.
  - Handled in the dashboard header flag dropdown in [dashboard.php](file:///c:/Users/Rohan/Desktop/hmssaas/Multi-Hospital/application/modules/home/views/dashboard.php). Fixed a bug where display labels in the Patient/Doctor dropdown were hardcoded to 'عربى' (Arabic) by changing them to load `ucfirst($language->language)` dynamically.

---

## 7. 💳 Payment Gateway Architecture

### Supported Gateways
| Gateway | Status | Credentials Used |
|---------|--------|-----------------|
| PayPal | ✅ | APIUsername, APIPassword, APISignature |
| Stripe | ✅ | secret (Secret Key), publish (Publish Key) |
| Pay U Money | ✅ | merchant_key, salt |
| Paystack | ✅ | public_key, secret |
| SSLCOMMERZ | ✅ | APIUsername (Store ID), APIPassword (Store Password) |

### SSLCOMMERZ DB Column Mapping
```
paymentGateway.APIUsername = SSLCOMMERZ Store ID
paymentGateway.APIPassword = SSLCOMMERZ Store Password
paymentGateway.status      = 'test' (sandbox) OR 'live'
```

### SSLCOMMERZ Module Files
- Controller: `modules/sslcommerz/controllers/Sslcommerz.php`
- State Table: `sslcommerz_payments_state` (created by migration)
- Endpoints: `/sslcommerz/initiate_payment`, `/sslcommerz/success`, `/sslcommerz/fail`, `/sslcommerz/cancel`, `/sslcommerz/ipn`

### Sidebar Links (All Panels)
- **Contact With Us:** `mailto:fctbd1@gmail.com`
- **Help Center:** `https://help.fstio.com`
- File: `modules/home/views/menu.php` (lines ~1264-1277 and ~1707-1722)

---

## 8. 🚀 Deployment Playbook

### Production Environment
- **URL:** `https://hms.fstio.com`
- **Coolify Dashboard:** `http://36.50.40.224:8000`
- **Container:** php:8.1-apache (custom Dockerfile)

### Standard Deploy Flow
1. Make code changes
2. `git add -A; git commit -m "description"`
3. `git push origin master`
4. Coolify auto-deploys (webhook or manual trigger from dashboard)

### One-Time DB Migration
After first deploy with new SQL migration:
1. Visit `https://hms.fstio.com/run-migration.php`
2. Verify output shows `[OK]` for all statements
3. **DELETE `run-migration.php` from the server immediately after!**

### Known Quirks
- **PHP 8.1 HMVC:** `MX_Controller` and `MX_Loader` require null-coalescing patches for `controller_suffix` and `object_name`
- **Composer:** `vendor/` must be built during Docker build (not committed to Git)
- **Build Context:** `.dockerignore` excludes heavy directories to keep build context ~2 MB
- **500 Error on Language Switch:** Fixed by patching HMVC null-handling
