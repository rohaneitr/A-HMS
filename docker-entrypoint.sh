#!/bin/bash
# NOTE: Do NOT use 'set -e' here — we handle errors manually
# to prevent container crash from non-critical failures

# =========================================================================
# ENTRYPOINT: Import DB + create ci_sessions table before Apache starts
# =========================================================================

# Read env vars — avoid bash history expansion with special chars like !
DB_HOST="${DB_HOST:-db}"
DB_USER="${DB_USER:-root}"
DB_NAME="${DB_NAME:-hmssaas}"
SQL_FILE="/docker-entrypoint-initdb.d/database_tables.sql"

# Use a temp file to pass password safely to mysql (avoids ! history expansion)
MYSQL_PWD_FILE=$(mktemp)
echo "[client]" > "$MYSQL_PWD_FILE"
echo "password=${DB_PASS:-rootpassword}" >> "$MYSQL_PWD_FILE"
chmod 600 "$MYSQL_PWD_FILE"

MYSQL_OPTS="--defaults-extra-file=${MYSQL_PWD_FILE} -h${DB_HOST} -u${DB_USER} ${DB_NAME}"

echo "[entrypoint] Waiting for database at ${DB_HOST}..."
MAX_RETRIES=30
RETRY=0
while true; do
    if mysql --defaults-extra-file="${MYSQL_PWD_FILE}" -h"${DB_HOST}" -u"${DB_USER}" "${DB_NAME}" -e "SELECT 1;" >/dev/null 2>&1; then
        echo "[entrypoint] DB is ready!"
        break
    fi
    RETRY=$((RETRY + 1))
    if [ $RETRY -ge $MAX_RETRIES ]; then
        echo "[entrypoint] WARNING: DB not reachable after ${MAX_RETRIES} retries. Starting Apache anyway."
        break
    fi
    echo "[entrypoint] DB not ready, retrying in 2s... (${RETRY}/${MAX_RETRIES})"
    sleep 2
done

echo "[entrypoint] Checking if DB needs initialization..."
TABLE_COUNT=$(mysql --defaults-extra-file="${MYSQL_PWD_FILE}" -h"${DB_HOST}" -u"${DB_USER}" "${DB_NAME}" \
    --skip-column-names \
    -e "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema='${DB_NAME}';" 2>/dev/null || echo "0")

echo "[entrypoint] Found ${TABLE_COUNT} tables in DB."

if [ "${TABLE_COUNT}" -le "1" ] && [ -f "${SQL_FILE}" ]; then
    echo "[entrypoint] Importing database schema from ${SQL_FILE}..."
    mysql --defaults-extra-file="${MYSQL_PWD_FILE}" -h"${DB_HOST}" -u"${DB_USER}" "${DB_NAME}" < "${SQL_FILE}" 2>&1 \
        && echo "[entrypoint] Database import SUCCESS!" \
        || echo "[entrypoint] WARNING: Database import had errors (may be partially imported)."
else
    echo "[entrypoint] DB already initialized (${TABLE_COUNT} tables). Skipping import."
fi

echo "[entrypoint] Ensuring ci_sessions table exists..."
mysql --defaults-extra-file="${MYSQL_PWD_FILE}" -h"${DB_HOST}" -u"${DB_USER}" "${DB_NAME}" -e "
CREATE TABLE IF NOT EXISTS ci_sessions (
    id varchar(128) NOT NULL,
    ip_address varchar(45) NOT NULL,
    timestamp int(10) unsigned DEFAULT 0 NOT NULL,
    data blob NOT NULL,
    PRIMARY KEY (id),
    KEY ci_sessions_timestamp (timestamp)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
" 2>/dev/null \
    && echo "[entrypoint] ci_sessions table: READY" \
    || echo "[entrypoint] ci_sessions: could not verify (DB may be unavailable)"

# Cleanup temp file
rm -f "$MYSQL_PWD_FILE"

echo "[entrypoint] Starting Apache..."
exec apache2-foreground
