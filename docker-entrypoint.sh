#!/bin/sh
set -e

# Configure Apache to listen on Render's dynamic $PORT (default 10000)
PORT_NUM=${PORT:-10000}
sed -i "s/Listen 80/Listen ${PORT_NUM}/g" /etc/apache2/ports.conf
sed -i "s/<VirtualHost \*:80>/<VirtualHost \*:${PORT_NUM}>/g" /etc/apache2/sites-available/*.conf

# Run database migrations and cache Laravel configurations
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Start Apache in foreground
exec apache2-foreground
