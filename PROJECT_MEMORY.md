# 🧠 A+HMS — Project Memory & Architecture Guide
> **Last Updated:** 2026-06-24 | **Version:** 3.0 | **Vendor:** Fast Technologies

This document is the **central reference** for the A+HMS SaaS Hospital Management System — codebase architecture, database schema, security hardening, branding, deployment playbooks, and completed work log.

---

## 1. ⚙️ Technology Stack

| Layer | Technology |
|-------|-----------|
| Backend Framework | CodeIgniter 3.x + HMVC extension |
| PHP Version | 8.1-Apache (tested, 8.3 compatible) |
| Authentication | Ion Auth Library v2.5.2 |
| Database | MariaDB 10.6+ / MySQL 8.x |
| Sessions | Database-backed (`ci_sessions` InnoDB) |
| Frontend | AdminLTE 3 + Bootstrap 4 + jQuery |
| Charts | Chart.js, Morris.js, Flot, Sparkline |
| Theme Override | `common/css/custom-style.css` (CSS-only, safe) |
| Deployment | Docker (php:8.1-apache) via Coolify |

---

## 2. 📁 Key Files & Directory Map

```
hmssaas/
├── Dockerfile                          # Custom php:8.1-apache build
├── .dockerignore                       # Keeps build context ~2 MB
├── docker-compose.yml                  # Local dev orchestration
├── PROJECT_MEMORY.md                   # ← THIS FILE
└── Multi-Hospital/
    ├── index.php                       # App entry (error_reporting: production-safe)
    ├── application/
    │   ├── config/
    │   │   ├── config.php              # Sessions, CSRF, cookie security
    │   │   ├── database.php            # DB connection (env-driven)
    │   │   ├── hooks.php               # pre_controller hook registration
    │   │   └── ion_auth.php            # Auth rules (bcrypt cost, lockout)
    │   ├── hooks/
    │   │   └── required.php            # Global auth middleware
    │   ├── language/
    │   │   ├── english/system_syntax_lang.php  # Added: store_id, store_password keys
    │   │   └── bangla/                 # Full Bengali translation
    │   ├── migrations/
    │   │   └── update_branding_2026.sql  # ✅ ALREADY RAN — branding + SSLCOMMERZ seed
    │   ├── modules/
    │   │   ├── auth/                   # Login/logout (session fixation patched)
    │   │   ├── home/views/
    │   │   │   ├── dashboard.php       # <head> CSS loads + Inter font
    │   │   │   ├── menu.php            # ⭐ Sidebar: contact=fctbd1@gmail.com, help=help.fstio.com
    │   │   │   └── footer.php          # CSRF injection engine (jQuery + Axios)
    │   │   ├── pgateway/               # Payment gateway CRUD
    │   │   │   ├── controllers/Pgateway.php  # SSLCOMMERZ validation added
    │   │   │   └── views/settings.php        # SSLCOMMERZ Store ID/Password form added
    │   │   ├── sslcommerz/             # SSLCOMMERZ payment processing
    │   │   │   └── controllers/Sslcommerz.php  # Uses APIUsername/APIPassword fields
    │   │   ├── request/controllers/
    │   │   │   └── Request.php         # Hospital approval: seeds SSLCOMMERZ gateway
    │   │   └── superadmin/, hospital/, patient/, finance/ ...
    │   └── third_party/MX/
    │       ├── Controller.php          # Patched: null-coalesce controller_suffix
    │       └── Loader.php              # Patched: null-coalesce object_name
    ├── common/css/
    │   └── custom-style.css            # ⭐⭐ MASTER THEME FILE — edit here for design
    ├── adminlte/dist/css/
    │   ├── adminlte.min.css            # DO NOT MODIFY
    │   └── changes.css                 # AdminLTE-specific tweaks only
    └── uploads/
        ├── logo.png                    # A+HMS logo (used in sidebar header)
        └── favicon.png                 # Browser tab icon
```

---

## 3. 🛡️ Security Hardening — Completed Patches

| ID | Vulnerability | Fix Applied |
|----|--------------|-------------|
| `SESS-001/002` | File-based session bleed in `/tmp` | Database session driver (`ci_sessions`) |
| `AUTH-001` | Session fixation on login | `sess_regenerate(TRUE)` in Auth.php |
| `PRIV-001/002` | Missing `exit()` after `redirect()` | Guards in Home.php + Superadmin.php constructors |
| `CSRF-001` | No CSRF on AJAX | Global jQuery + Axios CSRF injection in footer.php |
| `COOK-001` | Insecure cookies | `httponly=TRUE`, dynamic `secure` via `X-Forwarded-Proto` |
| `PHP8-001` | HMVC null crash on PHP 8.1 | Null-coalesce in MX/Controller.php + MX/Loader.php |

---

## 4. 🗄️ Database — Key Tables

### `ci_sessions` (Session storage)
```sql
CREATE TABLE IF NOT EXISTS `ci_sessions` (
    `id`         varchar(128)     NOT NULL,
    `ip_address` varchar(45)      NOT NULL,
    `timestamp`  int(10) unsigned DEFAULT 0 NOT NULL,
    `data`       blob             NOT NULL,
    PRIMARY KEY (`id`),
    KEY `ci_sessions_timestamp` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### `paymentGateway` (Payment credentials)
```
Columns: id, name, APIUsername, APIPassword, APISignature,
         merchant_key, salt, public_key, secret, publish, status, hospital_id
```

### `sslcommerz_payments_state` (SSLCOMMERZ transaction tracker)
```
Columns: id, tran_id, patient_id, payment_id, amount,
         user_id, hospital_id, redirect_link, status, created_at
Status values: pending → success / failed / cancelled
```

---

## 5. 💳 Payment Gateway Architecture

### Supported Gateways (all seeded on hospital approval)
| Gateway | DB Credentials | Status |
|---------|---------------|--------|
| PayPal | APIUsername, APIPassword, APISignature | ✅ Live |
| Stripe | secret, publish | ✅ Live |
| Pay U Money | merchant_key, salt | ✅ Live |
| Paystack | public_key, secret | ✅ Live |
| **SSLCOMMERZ** | **APIUsername** (Store ID), **APIPassword** (Store Password) | ✅ **Added & Seeded** |

### SSLCOMMERZ Integration Details
- **Module:** `modules/sslcommerz/controllers/Sslcommerz.php`
- **Reads from DB:** `APIUsername` = Store ID | `APIPassword` = Store Password
- **State Table:** `sslcommerz_payments_state`
- **Sandbox URL:** `https://sandbox.sslcommerz.com/gwprocess/v4/api.php`
- **Live URL:** `https://securepay.sslcommerz.com/gwprocess/v4/api.php`
- **Validation URL:** `https://securepay.sslcommerz.com/validator/api/validationserverAPI.php`
- **Endpoints:** `/sslcommerz/initiate_payment`, `/success`, `/fail`, `/cancel`, `/ipn`
- **DB Seeding:** Done ✅ — All existing hospitals have SSLCOMMERZ row (via migration)

---

## 6. 🎨 Branding & UI Theme

### Fast Technologies Identity
| Field | Value |
|-------|-------|
| App Name | **A+HMS** |
| Company | **Fast Technologies** |
| Email | `fctbd1@gmail.com` |
| Phone | `+8801759190782` |
| Help Center | `https://help.fstio.com` |
| Logo | `uploads/logo.png` |
| Favicon | `uploads/favicon.png` |

### UI Theme — Purple + Dark Slate (Premium Luxury)
| Element | Value |
|---------|-------|
| Primary Color | `#7c3aed` (Vibrant Purple) |
| Sidebar BG | `#1e1b4b` → `#13103a` (Deep Indigo gradient) |
| Body BG | `#f0effe` (Light lavender) |
| Accent | `#a78bfa` (Soft violet) |
| Font | Inter (Google Fonts — CSS @import) |
| Cards | Rounded `10px`, subtle shadow |
| Buttons | Gradient with hover lift |
| Tables | Dark indigo header |
| Login Page | Full-screen purple gradient |
| **Override File** | `common/css/custom-style.css` ← **ONLY edit here** |
| CSS Load Point | `dashboard.php` line 111 (last — highest priority) |

### Sidebar Links (All User Panels — menu.php)
- **Contact With Us** → `mailto:fctbd1@gmail.com`
- **Help Center** → `https://help.fstio.com` (opens in new tab)
- **File:** `modules/home/views/menu.php` (~lines 1264–1277 hospital, 1707–1722 superadmin)

### Bengali Language Support
- **Translation:** `application/language/bangla/system_syntax_lang.php`
- **DB Entry:** `language` table — id=17, folder=bangla, flag=bd
- **Login selector:** added `bd` flag option in login.php
- **Dashboard:** Language dropdown fixed to use `ucfirst($language->language)` dynamically

---

## 7. 🔒 Strict Development Rules

1. **CSS Only for Design** — All UI changes go in `common/css/custom-style.css`. Never touch `adminlte.min.css` or `bootstrap.min.css`.
2. **Preserve HTML IDs** — jQuery selectors rely on existing `id` attributes. Never rename them.
3. **No Core Vendor Edits** — `vendor/`, `system/` are off-limits. Use MX_Controller overrides.
4. **PHP 8.1+ Safe** — Use null-coalescing, avoid deprecated functions, no `strtolower(null)`.
5. **SSLCOMMERZ Column Mapping** — `APIUsername` = Store ID, `APIPassword` = Store Password (existing paymentGateway table columns, no schema change needed).
6. **Migration Scripts** — Always delete after execution. Never leave utility PHP files on the server.

---

## 8. 🚀 Deployment Playbook

### Production Environment
| Item | Value |
|------|-------|
| Live URL | `https://hms.fstio.com` |
| Coolify Dashboard | `http://36.50.40.224:8000` |
| Container | `php:8.1-apache` (custom Dockerfile) |
| Git Repo | `https://github.com/rohaneitr/A-HMS.git` |
| Branch | `master` |

### Standard Deploy Flow
```bash
git add -A
git commit -m "feat/fix: description"
git push origin master
# Then trigger Deploy in Coolify dashboard
```

### Known Build Quirks
| Issue | Solution |
|-------|---------|
| `vendor/` not in Git | Composer runs inside Dockerfile during build |
| Build context too large | `.dockerignore` reduces to ~2 MB |
| PHP 8.1 HMVC null crash | MX patches applied (Controller.php + Loader.php) |
| 500 on language switch | Same HMVC null patch |

### Future DB Migrations (How-To)
If future DB changes are needed:
1. Write SQL in `application/migrations/` directory
2. Create a temporary `run-migration.php` in `Multi-Hospital/`
3. Commit + push + deploy
4. Visit `https://hms.fstio.com/run-migration.php` and verify `[OK]`
5. **Immediately** `git rm run-migration.php` + commit + push + redeploy

---

## 9. ✅ Completed Work Log

| Date | Task | Status |
|------|------|--------|
| 2026-06-24 | White-label rebranding to A+HMS / Fast Technologies | ✅ Done |
| 2026-06-24 | PHP 8.1 HMVC null-handling patches | ✅ Done |
| 2026-06-24 | .dockerignore + Dockerfile optimization | ✅ Done |
| 2026-06-24 | Bengali language support | ✅ Done |
| 2026-06-24 | Database session driver (security) | ✅ Done |
| 2026-06-24 | CSRF global injection engine | ✅ Done |
| 2026-06-24 | Contact email → fctbd1@gmail.com (all panels) | ✅ Done |
| 2026-06-24 | Help Center → help.fstio.com (all panels) | ✅ Done |
| 2026-06-24 | SSLCOMMERZ payment gateway integration | ✅ Done |
| 2026-06-24 | SSLCOMMERZ seeded for all existing hospitals | ✅ Done |
| 2026-06-24 | Purple + Dark Slate modern UI theme deployed | ✅ Done |
| 2026-06-24 | run-migration.php deleted after use (security) | ✅ Done |
