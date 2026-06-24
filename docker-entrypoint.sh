#!/bin/bash
set -e

# =========================================================================
# ENTRYPOINT: Import DB + create ci_sessions table before Apache starts
# =========================================================================

DB_HOST="${DB_HOST:-db}"
DB_USER="${DB_USER:-root}"
DB_PASS="${DB_PASS:-rootpassword}"
DB_NAME="${DB_NAME:-hmssaas}"
SQL_FILE="/docker-entrypoint-initdb.d/database_tables.sql"

echo "[entrypoint] Waiting for database at ${DB_HOST}..."
MAX_RETRIES=30
RETRY=0
while ! php -r "
    \$m = @new mysqli('${DB_HOST}', '${DB_USER}', '${DB_PASS}', '${DB_NAME}');
    exit(\$m->connect_error ? 1 : 0);
" 2>/dev/null; do
    RETRY=$((RETRY + 1))
    if [ $RETRY -ge $MAX_RETRIES ]; then
        echo "[entrypoint] WARNING: DB not reachable after ${MAX_RETRIES} retries. Starting anyway."
        break
    fi
    echo "[entrypoint] DB not ready, retrying in 2s... (${RETRY}/${MAX_RETRIES})"
    sleep 2
done

echo "[entrypoint] Checking if DB needs initialization..."
TABLE_COUNT=$(mysql -h"${DB_HOST}" -u"${DB_USER}" -p"${DB_PASS}" "${DB_NAME}" \
    -e "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema='${DB_NAME}';" \
    --skip-column-names 2>/dev/null || echo "0")

if [ "${TABLE_COUNT}" -le "1" ] && [ -f "${SQL_FILE}" ]; then
    echo "[entrypoint] Importing database schema and data from ${SQL_FILE}..."
    mysql -h"${DB_HOST}" -u"${DB_USER}" -p"${DB_PASS}" "${DB_NAME}" < "${SQL_FILE}" 2>&1
    echo "[entrypoint] Database import complete!"
else
    echo "[entrypoint] Database already has ${TABLE_COUNT} tables, skipping import."
fi

echo "[entrypoint] Creating ci_sessions table if not exists..."
mysql -h"${DB_HOST}" -u"${DB_USER}" -p"${DB_PASS}" "${DB_NAME}" -e "
CREATE TABLE IF NOT EXISTS ci_sessions (
    id varchar(128) NOT NULL,
    ip_address varchar(45) NOT NULL,
    timestamp int(10) unsigned DEFAULT 0 NOT NULL,
    data blob NOT NULL,
    PRIMARY KEY (id),
    KEY ci_sessions_timestamp (timestamp)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
" 2>/dev/null && echo "[entrypoint] ci_sessions table: READY" || echo "[entrypoint] ci_sessions: using existing"

echo "[entrypoint] Starting Apache..."
exec apache2-foreground
