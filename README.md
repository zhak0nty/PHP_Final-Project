## Medical / Beauty Booking API (Laravel)

Laravel 13 doctor booking API with Sanctum authentication, roles (admin/doctor/client), CRUD resources, and basic tests.

### Running the project

```bash
git clone <repo>
cd php
cp .env.example .env
php artisan key:generate

# Create the SQLite file if it does not exist yet
touch database/database.sqlite

php artisan migrate --seed
php artisan serve
```

Default demo accounts:

- **Admin**: `admin@example.com` / `password`
- **Client**: `client@example.com` / `password`
- **Doctors**: `doctor1@example.com` … `doctor15@example.com` / `password`

### Authentication

Protected endpoints expect a **Bearer Sanctum token**.

- **POST** `/api/register` — client registration  
  - body: `{ "name", "email", "password", "password_confirmation" }`  
  - response: `{ token, user }` (`user` is a flat JSON object from `UserResource`)
- **POST** `/api/login` — login  
  - body: `{ "email", "password" }`  
  - response: `{ token, user }`
- **POST** `/api/logout` — logout (Bearer token required)

Authorization header:

```http
Authorization: Bearer <token>
Accept: application/json
```

### Roles and access

- `admin` — manage doctors, services, slots, and reviews (`/api/doctors`, `/api/services`, `/api/time-slots`, `/api/reviews`)
- `doctor` — view own appointments (`/api/doctor/appointments`)
- `client` — create, view, and cancel appointments (`/api/appointments`)

Insufficient permissions return **403 Forbidden** with JSON `{ "message": "Forbidden." }`.

### Core entities and relationships

- `users` — all users; `role` is `admin`, `doctor`, or `client`
- `doctors` — doctor profile, `user_id` (One‑to‑One: user↔doctor)
- `services` — services
- `doctor_service` — pivot (Many‑to‑Many: doctors↔services)
- `time_slots` — schedule slots for a doctor (One‑to‑Many: doctor→time_slots)
- `appointments` — bookings (One‑to‑Many: doctor→appointments, client(user)→appointments)

### CRUD endpoints

#### Admin (requires `admin` role)

- **Doctors** `/api/doctors`
  - `GET /api/doctors` — list
  - `POST /api/doctors` — create (`user_id`, `specialization?`, `bio?`)
  - `GET /api/doctors/{id}` — show
  - `PUT/PATCH /api/doctors/{id}` — update
  - `DELETE /api/doctors/{id}` — delete

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

- **Reviews** `/api/reviews`
  - `GET /api/reviews`
  - `POST /api/reviews` (`kind?`, `name?`, `phone?`, `text`)
  - `GET /api/reviews/{id}`
  - `PUT/PATCH /api/reviews/{id}`
  - `DELETE /api/reviews/{id}`

#### Client (`client` role)

- **Appointments**
  - `GET /api/appointments` — list own appointments
  - `POST /api/appointments` — create
    - body: `{ "doctor_id", "service_id", "time_slot_id" }`
  - `GET /api/appointments/{id}` — show own appointment
  - `PUT/PATCH /api/appointments/{id}` — reschedule own appointment
  - `DELETE /api/appointments/{id}` — delete own appointment
  - `POST /api/appointments/{id}/cancel` — cancel (status → `cancelled`)

#### Doctor (`doctor` role)

- `GET /api/doctor/appointments` — appointments for this doctor.

### Validation and errors

Form Request classes:

- `StoreDoctorRequest`
- `StoreServiceRequest`
- `StoreTimeSlotRequest`
- `StoreAppointmentRequest`
- `UpdateAppointmentRequest`
- `RegisterRequest`
- `LoginRequest`
- `StorePublicReviewRequest`
- `StoreReviewRequest`
- `UpdateReviewRequest`

Main rules:

- slots: `starts_at` not in the past, `ends_at` after `starts_at`
- when creating an appointment:
  - slot exists and belongs to the doctor
  - slot is not in the past
  - no other `scheduled` appointment for that slot
  - service exists and the doctor offers it

Error codes:

- **422** — validation errors (JSON `errors`)
- **403** — wrong role or access
- **404** — resource not found

### Notifications

After a successful booking, an `AppointmentCreated` event runs; a **queued job** (`NotifyAppointmentParticipants`) sends `AppointmentCreatedNotification` to the **doctor** (and to the **client** when `client_id` is set). Channel is `database` (no email/SMS). For async processing set `QUEUE_CONNECTION=database` (or `redis`) and run `php artisan queue:work`.

### API JSON shape

Single-model responses use Laravel **API Resources** and are wrapped in a top-level `data` key (e.g. `POST /api/appointments`, `GET /api/doctors/{id}`). Paginated lists return `data`, `links`, and `meta`. Register/login responses keep `user` as a plain object (no extra `data` wrapper).

### Laravel patterns in this repo

| Pattern | Where |
|--------|--------|
| **API Resources** | `app/Http/Resources/*Resource.php` |
| **Policy** | `AppointmentPolicy` — `view/update/delete/cancel` only for the appointment owner (client) |
| **Event + listener** | `AppointmentCreated` → `DispatchAppointmentNotificationJob` |
| **Queue / job** | `NotifyAppointmentParticipants` implements `ShouldQueue` |
| **Service layer** | `AppointmentService` (business rules + transactions) |
| **Pivot** | `doctor_service` (many-to-many doctors ↔ services) |

### Tests

Run:

```bash
php artisan test
```

Covered by feature tests:

1. Registration and token
2. Login and token
3. Client can create an appointment
4. Cannot book an occupied slot
5. Client cannot create doctor/service (403)
6. Client can cancel own appointment; cannot cancel someone else’s (policy)
7. Client can show/update/delete own appointment
8. Admin has full CRUD for API reviews
9. Custom 404 page, unknown `/api/*` paths return JSON errors (`AppStabilityTest`)

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
