#!/bin/sh
set -eu

if [ ! -f .env ]; then
    cp .env.example .env
fi

# Docker Compose sets DB_DATABASE inside a persistent volume.
DB_FILE="${DB_DATABASE:-/var/www/html/database/database.sqlite}"
mkdir -p "$(dirname "$DB_FILE")"
touch "$DB_FILE"

if [ -z "${APP_KEY:-}" ] && grep -Eq '^APP_KEY=$' .env; then
    php artisan key:generate --force --no-interaction
fi

php artisan migrate --force --no-interaction

if [ "${SEED_DEMO:-false}" = "true" ] && [ ! -f "${DB_FILE}.demo-seeded" ]; then
    php artisan db:seed --force --no-interaction
    touch "${DB_FILE}.demo-seeded"
fi

exec "$@"
