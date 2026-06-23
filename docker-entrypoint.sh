#!/bin/bash
set -e

DB_HOST="${DB_HOST:-db}"
DB_USER="${DB_USER:-root}"
DB_PASS="${DB_PASS:-rootpassword}"
DB_NAME="${DB_NAME:-hmssaas}"

echo "==> Waiting for MariaDB at $DB_HOST..."
for i in $(seq 1 30); do
    if mysqladmin ping -h "$DB_HOST" -u "$DB_USER" -p"$DB_PASS" --silent 2>/dev/null; then
        echo "==> MariaDB is ready!"
        break
    fi
    echo "    Attempt $i/30 — waiting 3s..."
    sleep 3
done

echo "==> Running database initialization..."

# Create ci_sessions table (required for CodeIgniter database session driver)
mysql -h "$DB_HOST" -u "$DB_USER" -p"$DB_PASS" "$DB_NAME" <<'SQL'
CREATE TABLE IF NOT EXISTS `ci_sessions` (
    `id`         varchar(128)         NOT NULL,
    `ip_address` varchar(45)          NOT NULL,
    `timestamp`  int(10) unsigned     DEFAULT 0 NOT NULL,
    `data`       blob                 NOT NULL,
    PRIMARY KEY (`id`),
    KEY `ci_sessions_timestamp` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL

echo "==> ci_sessions table ensured."
echo "==> Starting Apache..."
exec apache2-foreground
