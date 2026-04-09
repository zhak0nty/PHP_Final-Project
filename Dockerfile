# syntax=docker/dockerfile:1
# ---------------------------------------------------------------------------
# Environment versions (change image tags here when upgrading the stack)
#   Node 22 — Vite build | PHP 8.4-fpm-alpine — app runtime (aligned with composer.lock)
#   Nginx / MySQL — tags in docker-compose.yml
# ---------------------------------------------------------------------------

FROM node:22-alpine AS node-builder

WORKDIR /app

COPY package.json package-lock.json* ./
RUN npm ci

COPY vite.config.js ./
COPY resources/ ./resources/
COPY public/ ./public/

ARG VITE_APP_NAME=MedBooking
ENV VITE_APP_NAME=${VITE_APP_NAME}

RUN npm run build

FROM php:8.4-fpm-alpine

RUN apk add --no-cache \
    libzip-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    oniguruma-dev \
    icu-dev \
    sqlite-dev \
    linux-headers \
    $PHPIZE_DEPS \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo_mysql \
        pdo_sqlite \
        mbstring \
        zip \
        exif \
        pcntl \
        bcmath \
        gd \
        intl \
        opcache

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY composer.json composer.lock ./
RUN composer install \
    --no-dev \
    --no-scripts \
    --no-interaction \
    --optimize-autoloader \
    --prefer-dist

COPY --chown=www-data:www-data . .

COPY --from=node-builder --chown=www-data:www-data /app/public/build ./public/build

RUN cp .env.example .env \
    && php artisan key:generate --force --no-interaction \
    && composer dump-autoload --optimize --classmap-authoritative \
    && php artisan package:discover --ansi --no-interaction \
    && rm -f .env

RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

COPY docker/entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 9000

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
