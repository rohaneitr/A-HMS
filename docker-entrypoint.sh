#!/bin/bash
set -e

# =========================================================================
# ENTRYPOINT: Auto-create ci_sessions table before Apache starts
# =========================================================================
# The HMVC autoloader in CodeIgniter loads the session library BEFORE
# the pre_controller hook fires. This means the ci_sessions table MUST
# exist before the first HTTP request hits the app. This script ensures
# the table is created on every container start.
# =========================================================================

DB_HOST="${DB_HOST:-db}"
DB_USER="${DB_USER:-root}"
DB_PASS="${DB_PASS:-rootpassword}"
DB_NAME="${DB_NAME:-hmssaas}"

echo "[entrypoint] Waiting for database at ${DB_HOST}..."
MAX_RETRIES=30
RETRY=0
while ! php -r "new mysqli('${DB_HOST}', '${DB_USER}', '${DB_PASS}', '${DB_NAME}');" 2>/dev/null; do
    RETRY=$((RETRY + 1))
    if [ $RETRY -ge $MAX_RETRIES ]; then
        echo "[entrypoint] WARNING: Could not connect to DB after ${MAX_RETRIES} retries. Starting Apache anyway."
        break
    fi
    echo "[entrypoint] DB not ready, retrying in 2s... (${RETRY}/${MAX_RETRIES})"
    sleep 2
done

echo "[entrypoint] Creating ci_sessions table if not exists..."
php -r "
\$m = @new mysqli('${DB_HOST}', '${DB_USER}', '${DB_PASS}', '${DB_NAME}');
if (\$m->connect_error) {
    echo '[entrypoint] DB connection failed: ' . \$m->connect_error . PHP_EOL;
} else {
    \$m->query('CREATE TABLE IF NOT EXISTS ci_sessions (
        id varchar(128) NOT NULL,
        ip_address varchar(45) NOT NULL,
        timestamp int(10) unsigned DEFAULT 0 NOT NULL,
        data blob NOT NULL,
        PRIMARY KEY (id),
        KEY ci_sessions_timestamp (timestamp)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');
    \$r = \$m->query(\"SHOW TABLES LIKE 'ci_sessions'\");
    echo '[entrypoint] ci_sessions table: ' . (\$r->num_rows > 0 ? 'READY' : 'FAILED') . PHP_EOL;
    \$m->close();
}
"

echo "[entrypoint] Starting Apache..."
exec apache2-foreground
