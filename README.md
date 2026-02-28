## Medical / Beauty Booking API (Laravel)

API‑система записи к врачу/мастеру на Laravel 12 с аутентификацией через Sanctum, ролями (admin/doctor/client), CRUD сущностями и базовыми тестами.

### Запуск проекта

```bash
git clone <repo>
cd php
cp .env.example .env
php artisan key:generate

# создать SQLite файл (если ещё нет)
touch database/database.sqlite

php artisan migrate --seed
php artisan serve
```

По умолчанию используются учётные записи:

- **Admin**: `admin@example.com` / `password`
- **Client**: `client@example.com` / `password`
- **Doctors**: `doctor1@example.com`, `doctor2@example.com`, `doctor3@example.com` / `password`

### Аутентификация

Все защищённые эндпоинты используют **Bearer Sanctum token**.

- **POST** `/api/register` — регистрация клиента  
  - body: `{ "name", "email", "password", "password_confirmation" }`  
  - response: `{ token, user }`
- **POST** `/api/login` — логин  
  - body: `{ "email", "password" }`  
  - response: `{ token, user }`
- **POST** `/api/logout` — выход (требуется Bearer token)

Заголовок авторизации:

```http
Authorization: Bearer <token>
Accept: application/json
```

### Роли и доступ

- `admin` — управление врачами, услугами, слотами (`/api/doctors`, `/api/services`, `/api/time-slots`)
- `doctor` — просмотр своих записей (`/api/doctor/appointments`)
- `client` — создание/просмотр/отмена записей (`/api/appointments`)

При отсутствии прав возвращается **403 Forbidden** с JSON `{ "message": "Forbidden." }`.

### Основные сущности и связи

- `users` — все пользователи, поле `role` (`admin`, `doctor`, `client`)
- `doctors` — профиль врача, `user_id` (One‑to‑One: user↔doctor)
- `services` — услуги
- `doctor_service` — pivot (Many‑to‑Many: doctors↔services)
- `time_slots` — слоты расписания врача (One‑to‑Many: doctor→time_slots)
- `appointments` — записи (One‑to‑Many: doctor→appointments, client(user)→appointments)

### CRUD эндпоинты

#### Admin (требует роль `admin`)

- **Doctors** `/api/doctors`
  - `GET /api/doctors` — список
  - `POST /api/doctors` — создать врача (поля: `user_id`, `specialization?`, `bio?`)
  - `GET /api/doctors/{id}` — показать
  - `PUT/PATCH /api/doctors/{id}` — обновить
  - `DELETE /api/doctors/{id}` — удалить

- **Services** `/api/services`
  - `GET /api/services`
  - `POST /api/services` (`name`, `duration_minutes`, `description?`)
  - `GET /api/services/{id}`
  - `PUT/PATCH /api/services/{id}`
  - `DELETE /api/services/{id}`

- **Time slots** `/api/time-slots`
  - `GET /api/time-slots`
  - `POST /api/time-slots` (`doctor_id`, `starts_at`, `ends_at`)
  - `GET /api/time-slots/{id}`
  - `PUT/PATCH /api/time-slots/{id}`
  - `DELETE /api/time-slots/{id}`

#### Client (роль `client`)

- **Appointments**
  - `GET /api/appointments` — список своих записей
  - `POST /api/appointments` — создать запись
    - body: `{ "doctor_id", "service_id", "time_slot_id" }`
  - `POST /api/appointments/{id}/cancel` — отменить (status → `cancelled`)

#### Doctor (роль `doctor`)

- `GET /api/doctor/appointments` — список записей к врачу.

### Валидация и ошибки

Используются Form Request классы:

- `StoreDoctorRequest`
- `StoreServiceRequest`
- `StoreTimeSlotRequest`
- `StoreAppointmentRequest`

Основные проверки:

- слоты: `starts_at` не в прошлом, `ends_at` позже `starts_at`
- при создании записи:
  - слот существует и принадлежит врачу
  - слот не в прошлом
  - нет другой записи со статусом `scheduled` на этот слот
  - услуга существует и врач её оказывает

Коды ошибок:

- **422** — ошибки валидации (JSON с полем `errors`)
- **403** — нет роли/доступа
- **404** — ресурс не найден

### Notifications

При успешном создании записи:

- в `notifications` добавляются записи для **врача** и **клиента**
- канал — `database` (никаких email/SMS)

### Тесты

Запуск:

```bash
php artisan test
```

Покрыто feature‑тестами:

1. Регистрация + получение токена
2. Логин + получение токена
3. Клиент может создать запись
4. Нельзя создать запись на занятый слот
5. Клиент не может создавать doctor/service (403)

<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework. You can also check out [Laravel Learn](https://laravel.com/learn), where you will be guided through building a modern Laravel application.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
