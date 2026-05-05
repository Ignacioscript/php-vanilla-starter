
# Lesson 04: Configuration Files (`config/app.php` and `config/database.php`)

**Date:** 2026-05-03
**Course:** PHP Application Masterclass Study Track

## Learning Goals

By the end of this lesson, you should be able to:
- Explain what configuration files do in this project.
- Understand how `$env` values are transformed into app settings.
- Read and reason about `config/app.php` and `config/database.php`.
- Explain why default values (`??`) are used.
- Describe the bootstrap flow from `.env` to config arrays.

---

## 1) Why do we have a `config/` folder?

The `config/` folder is the bridge between:
- raw environment values (strings/booleans from `.env`), and
- normalized application settings your code can consume.

Instead of reading environment values everywhere in your app, you centralize them once in config files.

### Benefits
- Clear single source of truth.
- Easier testing (mock one config array).
- Easier refactoring.
- Cleaner controllers/services.

---

## 2) Your current `config/app.php`

File: `config/app.php`

It returns this shape:
- `name`
- `env`
- `debug`
- `url`
- `pokeapi_base_url`

### Important detail
This file expects an `$env` array to already exist when the file is included.

Example behavior:
- `'env' => $env['APP_ENV'] ?? 'local'`
- `'debug' => $env['APP_DEBUG'] ?? true`

So if a variable is missing, it falls back to a safe default.

---

## 3) Your current `config/database.php`

File: `config/database.php`

It returns database connection settings:
- `driver`
- `host`
- `port`
- `database`
- `username`
- `password`

Also built from `$env` with defaults:
- `'driver' => $env['DB_DRIVER'] ?? 'mysql'`
- `'host' => $env['DB_HOST'] ?? '127.0.0.1'`
- etc.

This design makes local setup easy while still allowing production overrides.

---

## 4) What the `??` operator does here

The null coalescing operator (`??`) means:
- use left value if it exists and is not null,
- otherwise use right fallback.

Example:

```php
$env['DB_PORT'] ?? '3306'
```



## 5) How config loading should work in bootstrap
The "Bridge Layer" is the architectural step between raw environment variables and your application logic.

### High-Level Flow:
1. **EnvironmentLoader** reads `.env` and returns an `$env` array.
2. **Bootstrap** passes that `$env` into the scope of your configuration files.
3. **`config/app.php`** and **`config/database.php`** return structured arrays.
4. **The App** stores these arrays in a central **Config Container/Repository**.
5. **Services/Controllers** read from the container (never from `.env` directly).



### Why this is good architecture:
It prevents **environment coupling**. Your business logic shouldn't care if a value came from a `.env` file, a hard-coded default, or a database—it just asks the Config Repository for a key.

---

## 6) Practical interpretation of each app key

### In `config/app.php`:
* **name:** The human-readable name of your application.
* **env:** The runtime mode (local, testing, production).
* **debug:** Controls whether verbose error messages are shown.
* **url:** The base URL used for generating absolute links or redirects.
* **pokeapi_base_url:** The base endpoint for external API requests.

### In `config/database.php`:
* Contains the **connection metadata** (Host, Port, DB Name) used to build your PDO DSN and manage credentials.

---

## 7) Common mistakes with configuration

*   **Mistake 1: Accessing .env directly from random classes**
    *Fix:* Always use a central config service. If you change your env loader later, you only have to fix it in one place.
*   **Mistake 2: Missing defaults**
    *Fix:* Use the Null Coalescing Operator (`??`). Without defaults, your app might crash if a teammate forgets to add a new key to their local `.env`.
*   **Mistake 3: Putting runtime logic in config files**
    *Fix:* Config files should return values. They should not send emails, connect to databases, or execute business logic.
*   **Mistake 4: Exposing secrets in config/*.php**
    *Fix:* Keep the actual passwords in `.env`. The config files should just reference those keys.

---

## 8) Suggested convention for growth
As your bookstore app grows, keep your configuration "Modular" by splitting concerns:
* `config/cache.php`: For Redis or Memcached settings.
* `config/services.php`: For third-party API keys (Stripe, Mailgun).
* `config/logging.php`: For defining where error logs are saved.

---

## 9) Mental Model

*   **.env** = Environment-specific **Input**.
*   **EnvironmentLoader** = The **Parser**.
*   **config/*.php** = The **Transformer** (Normalizes values and adds defaults).
*   **App** = The **Consumer** (Accesses values through a controlled point).

> **That is the clean boundary between infrastructure and application code.**

---

## Quick Check (Self-Quiz)
1. Why is `config/` considered a **bridge layer**?
2. What does the `??` operator protect your application from?
3. Which file should define the **DB host defaults**?
4. Why should services read from `config`, not `.env` directly?

---

## Practice Task
1.  **Timezone:** Add a new key `timezone` in `config/app.php` with a fallback to `'UTC'`.
2.  **Charset:** Add `DB_CHARSET` support in `config/database.php` with a fallback to `'utf8mb4'`.
3.  **Reflection:** Write 3 sentences explaining why these specific settings should be configurable rather than hard-coded.

---

## Next Lesson Preview
*   **Dependency Injection Container (PSR-11):** How the app manages object creation.
*   **Service Resolution:** How the app "finds" the tools it needs.
*   **Constructor Injection:** How to keep your classes decoupled and testable (crucial for your SDET work!).

