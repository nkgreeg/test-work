#!/bin/sh
set -e
php bin/console doctrine:migrations:migrate --no-interaction --allow-no-migration
php-fpm -D -R
exec nginx -g "daemon off;"
