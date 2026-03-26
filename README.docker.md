# Запуск MedBooking в Docker

## Вариант 1: Production (Nginx + PHP-FPM + MySQL)

1. Скопируйте `.env.example` в `.env` и настройте:

```bash
cp .env.example .env
```

В `.env` укажите для MySQL:

```
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=medbooking
DB_USERNAME=medbooking
DB_PASSWORD=secret
```

2. Соберите ассеты (если ещё не сделано):

```bash
npm run build
```

3. Соберите и запустите:

```bash
docker compose build
docker compose up -d
```

4. Выполните миграции и сиды:

```bash
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate --force
docker compose exec app php artisan db:seed
```

5. Откройте http://localhost:8000

---

## Вариант 2: Laravel Sail (рекомендуется для разработки)

Sail уже установлен в проекте. Первый запуск:

```bash
php artisan sail:install
# Выберите: mysql, (остальное по желанию)

./vendor/bin/sail up -d
./vendor/bin/sail artisan migrate --seed
```

Сайт: http://localhost

---

## Структура Docker-файлов

- `Dockerfile` — образ PHP 8.2 + расширения, сборка Vite-ассетов
- `docker-compose.yml` — app (PHP-FPM), nginx, mysql
- `docker/nginx/default.conf` — конфиг Nginx для Laravel
- `.dockerignore` — исключения при сборке образа
