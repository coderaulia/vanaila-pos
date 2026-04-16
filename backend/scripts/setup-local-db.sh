#!/usr/bin/env bash

set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$ROOT_DIR"

if [[ ! -f .env ]]; then
	cp .env.example .env
fi

read_env() {
	local key="$1"
	grep -E "^${key}=" .env | tail -n1 | cut -d'=' -f2- | sed -e 's/\r$//' -e 's/^"//' -e 's/"$//'
}

db_connection="$(read_env DB_CONNECTION)"
db_host="$(read_env DB_HOST)"
db_port="$(read_env DB_PORT)"
db_name="$(read_env DB_DATABASE)"
db_username="$(read_env DB_USERNAME)"
db_password="$(read_env DB_PASSWORD)"

if [[ "$db_connection" != "mysql" ]]; then
	echo "Expected DB_CONNECTION=mysql in backend/.env, got '${db_connection}'."
	exit 1
fi

if ! command -v mysql >/dev/null 2>&1; then
	echo "MySQL client is not installed. Install mysql-client and retry."
	exit 1
fi

mysql_args=(-h "$db_host" -P "$db_port" -u "$db_username")

if [[ -n "$db_password" ]]; then
	mysql_args+=("-p${db_password}")
fi

echo "Creating database '${db_name}' if it does not exist..."
mysql "${mysql_args[@]}" -e "CREATE DATABASE IF NOT EXISTS \`${db_name}\` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

echo "Running migrations and seeders..."
php artisan migrate:fresh --seed

echo "Local database setup complete."
