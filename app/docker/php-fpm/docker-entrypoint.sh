#!/bin/bash
set -e

if [ -n "$DATABASE_HOST" ]; then
  echo "En attente de MySQL..."
  while ! mysqladmin ping -h"$DATABASE_HOST" -P"${DATABASE_PORT:-3306}" --silent; do
    sleep 2
  done
fi

# Commandes d'initialisation
composer install --optimize-autoloader --no-interaction
php bin/console doctrine:schema:update --force --no-interaction

# Permissions (si nécessaire)
chown -R www-data:www-data var/

exec "$@"