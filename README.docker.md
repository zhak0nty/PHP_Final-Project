# MedBooking — Docker

Stack: **PHP-FPM** (Laravel), **Nginx**, **MySQL 8.0**, **Vite (Node)** build inside the image.

## Pinned versions

| Component | Where it is set | Default |
|-----------|-----------------|---------|
| PHP | `Dockerfile` (`FROM php:…`) | 8.4-fpm-alpine |
| Node (Vite) | `Dockerfile` (`FROM node:…`) | 22-alpine |
| Composer | `COPY --from=composer:2` in `Dockerfile` | 2.x |
| Nginx | `docker-compose.yml` | `nginx:1.27.4-alpine` |
| MySQL | `docker-compose.yml` | `mysql:8.0.40` |

Ports: HTTP — `8000` (`HTTP_PORT`), MySQL — `3306` (`MYSQL_PORT`).

## Quick start

1. Copy the environment file and edit if needed:

```bash
cp .env.example .env
```

For Docker, set MySQL (and optionally `APP_URL=http://localhost:8000`):

```env
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=medbooking
DB_USERNAME=medbooking
DB_PASSWORD=secret
DB_ROOT_PASSWORD=secret
```

2. Build and run:

```bash
docker compose build
docker compose up -d
```

3. One-time: generate key and seed if the entrypoint could not finish (e.g. empty `.env` on the host):

```bash
docker compose exec app php artisan key:generate --force
docker compose exec app php artisan migrate --force
docker compose exec app php artisan db:seed
```

4. Open **http://localhost:8000** (or your `APP_URL` from `.env`).

To upgrade **PHP** or **Node**, change the `FROM` tags in `Dockerfile` and rebuild (`docker compose build --no-cache`).

## Files

| File | Purpose |
|------|---------|
| `Dockerfile` | multi-stage: Node → Vite build, PHP + extensions, Composer |
| `docker-compose.yml` | `app`, `nginx`, `mysql`, volumes |
| `docker/nginx/default.conf` | Nginx → `app:9000` (PHP-FPM) |
| `docker/entrypoint.sh` | `composer install` if `vendor` missing, key, migrations, `php-fpm` |
| `.dockerignore` | build context exclusions |

## Laravel Sail

Alternative for local development: `./vendor/bin/sail` (see the official Laravel docs).
