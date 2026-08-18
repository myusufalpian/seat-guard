<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

# SeatGuard

Concurrent-safe seat booking system — showcase: zero double booking under
concurrent, multi-instance load. Docs: `docs/PRD-v1.1.md`,
`docs/rfc/RFC-001-concurrent-safe-booking.md`, backlog `docs/tasks/`.

## Quickstart (Docker)

```bash
docker compose up --build
# LB + 3 app instances:  http://localhost/healthz
# Keycloak admin:        http://localhost:8080 (admin/admin)
# Mailpit:               http://localhost:8025
```

Demo users (realm `seatguard`): `customer/customer123`,
`eventadmin/eventadmin123`, `superadmin/superadmin123`.

> Keycloak `--import-realm` hanya meng-import realm yang belum ada. Setelah
> mengedit `deploy/keycloak/realm-seatguard.json`, reset realm:
> `make keycloak-realm-reset` (atau
> `docker compose down keycloak keycloak-db && docker volume rm seatguard_keycloakdbdata
> && docker compose up -d keycloak`).

### Deployment checklist (VPS/demo publik)

Sebelum demo publik, aktifkan:

1. **TLS & HSTS** — Caddy: ganti `:80` dengan domain (`example.com { ... }`); Caddy
   auto-issue cert. Tambah `Strict-Transport-Security "max-age=31536000; includeSubDomains"`.
2. **TLS antar-service** — postgres `ssl=on` + cert; `KEYCLOAK_BASE_URL=https`.
3. **APP_KEY via secret** (bukan bake image): inject env saat `docker compose up`.
4. **Rate-limit per-IP** aktif penuh (TrustProxies sudah dikonfigurasi, `TRUSTED_PROXIES`).

## Local dev

```bash
composer install
cp .env.example .env && php artisan key:generate
php artisan migrate
php artisan test          # Pest
vendor/bin/pint --dirty   # format
vendor/bin/phpstan analyse --memory-limit=1G
php artisan octane:start  # FrankenPHP worker mode
```

---

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

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

In addition, [Laracasts](https://laracasts.com) contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

You can also watch bite-sized lessons with real-world projects on [Laravel Learn](https://laravel.com/learn), where you will be guided through building a Laravel application from scratch while learning PHP fundamentals.

## Agentic Development

Laravel's predictable structure and conventions make it ideal for AI coding agents like Claude Code, Cursor, and GitHub Copilot. Install [Laravel Boost](https://laravel.com/docs/ai) to supercharge your AI workflow:

```bash
composer require laravel/boost --dev

php artisan boost:install
```

Boost provides your agent 15+ tools and skills that help agents build Laravel applications while following best practices.

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
