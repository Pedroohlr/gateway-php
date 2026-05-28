#!/bin/bash
set -e

php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan migrate --force
php artisan db:seed --class=AppSetupSeeder --force

exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
