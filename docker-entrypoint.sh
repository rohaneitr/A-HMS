#!/bin/bash
# NOTE: No 'set -e' — handle errors manually to prevent container crash

# =========================================================================
# ENTRYPOINT: Import DB + create ci_sessions table before Apache starts
# =========================================================================

DB_HOST="${DB_HOST:-db}"
DB_USER="${DB_USER:-root}"
DB_NAME="${DB_NAME:-hmssaas}"
SQL_FILE="/docker-entrypoint-initdb.d/database_tables.sql"

# Use temp file for password to safely handle special chars (like !)
MYSQL_PWD_FILE=$(mktemp)
printf '[client]\npassword=%s\n' "${DB_PASS:-rootpassword}" > "$MYSQL_PWD_FILE"
chmod 600 "$MYSQL_PWD_FILE"

run_mysql() {
    mysql --defaults-extra-file="${MYSQL_PWD_FILE}" -h"${DB_HOST}" -u"${DB_USER}" "${DB_NAME}" --skip-ssl "$@"
}

echo "[entrypoint] Waiting for database at ${DB_HOST}..."
MAX_RETRIES=30
RETRY=0
while true; do
    if run_mysql -e "SELECT 1;" >/dev/null 2>&1; then
        echo "[entrypoint] DB is ready!"
        break
    fi
    RETRY=$((RETRY + 1))
    if [ $RETRY -ge $MAX_RETRIES ]; then
        echo "[entrypoint] WARNING: DB not reachable. Starting Apache anyway."
        break
    fi
    echo "[entrypoint] DB not ready, retrying in 2s... (${RETRY}/${MAX_RETRIES})"
    sleep 2
done

# Check for a core application table (not ci_sessions) to determine if import needed
echo "[entrypoint] Checking if core tables exist..."
USERS_TABLE=$(run_mysql --skip-column-names \
    -e "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema='${DB_NAME}' AND table_name='users';" \
    2>/dev/null || echo "0")

echo "[entrypoint] users table count: ${USERS_TABLE}"

if [ "${USERS_TABLE}" = "0" ] && [ -f "${SQL_FILE}" ]; then
    echo "[entrypoint] Core tables missing. Importing from ${SQL_FILE}..."
    run_mysql < "${SQL_FILE}" 2>&1 \
        && echo "[entrypoint] Database import SUCCESS!" \
        || echo "[entrypoint] WARNING: Import had errors (may be partial)."
else
    echo "[entrypoint] Core tables already exist. Skipping import."
fi

# Always ensure ci_sessions exists
echo "[entrypoint] Ensuring ci_sessions table exists..."
run_mysql -e "
CREATE TABLE IF NOT EXISTS ci_sessions (
    id varchar(128) NOT NULL,
    ip_address varchar(45) NOT NULL,
    timestamp int(10) unsigned DEFAULT 0 NOT NULL,
    data blob NOT NULL,
    PRIMARY KEY (id),
    KEY ci_sessions_timestamp (timestamp)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
" 2>/dev/null \
    && echo "[entrypoint] ci_sessions: READY" \
    || echo "[entrypoint] ci_sessions: could not verify"

rm -f "$MYSQL_PWD_FILE"

echo "[entrypoint] Starting Apache..."
exec apache2-foreground
