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
- **[Database/](file:///c:/Users/Rohan/Desktop/hmssaas/Database/)** — Initial database scripts.
- **[Dockerfile](file:///c:/Users/Rohan/Desktop/hmssaas/Dockerfile)** — Builds the custom `php:8.1-apache` container.
- **[docker-compose.yml](file:///c:/Users/Rohan/Desktop/hmssaas/docker-compose.yml)** — Local environment orchestration file.

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
2. **Override Styles Safely:** Upgrade the UI/UX by appending styles to a dedicated custom style file. Avoid editing core libraries' CSS files (e.g. `adminlte.css`, `bootstrap.css`).
3. **No Core Frame Modifications:** Never modify files in `vendor/` or third-party core CodeIgniter files. Put overrides inside modular extension controllers (`MX_Controller`).
4. **PHP 8.3 Guidelines:** Maintain strict typing, avoid deprecated functions, and ensure type-safety to prevent fatal engine crashes in high-tier execution environments.
