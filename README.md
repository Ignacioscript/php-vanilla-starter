# PHP Vanilla Starter (2026)

A professional, framework-free PHP starter template using PSR-4/7/11 and a PokeAPI example.

## Requirements
- PHP 8.4+
- Composer

## Setup
```bash
composer install
cp .env.example .env
php -S localhost:8000 -t public
```

Visit:
```
http://localhost:8000/pokemon/pikachu
```

## Structure
- `app/` application core
- `config/` configuration
- `public/` web root
- `resources/views/` templates
- `storage/` logs/cache

## Notes
- Uses a minimal DI container.
- Uses a custom router and immutable request/response objects.
- Includes a native renderer and security helpers.
