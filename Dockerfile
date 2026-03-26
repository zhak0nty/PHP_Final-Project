FROM node:22-alpine AS node-builder

WORKDIR /app

COPY package*.json ./
RUN npm ci

COPY vite.config.js ./
COPY resources/ ./resources/
COPY public/ ./public/

ARG VITE_APP_NAME=MedBooking
ENV VITE_APP_NAME=${VITE_APP_NAME}
RUN npm run build

FROM php:8.2-fpm-alpine

RUN apk add --no-cache \
    libzip-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    oniguruma-dev \
    icu-dev \
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

# Копируем код приложения
COPY --chown=www-data:www-data . .

# Установка зависимостей PHP (без dev)
RUN composer install --no-dev --no-scripts --optimize-autoloader --prefer-dist

# Копируем собранные ассеты из node-builder (после COPY, чтобы не перезаписать)
COPY --from=node-builder --chown=www-data:www-data /app/public/build ./public/build

# Права на storage и cache
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

COPY docker/entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 9000

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
