#!/bin/sh
set -e

cd /var/www/html

# Установка зависимостей при первом запуске (если vendor пуст из-за volume)
if [ ! -f vendor/autoload.php ]; then
    composer install --no-interaction
fi

# Миграции
php artisan migrate --force --no-interaction 2>/dev/null || true

exec php-fpm
