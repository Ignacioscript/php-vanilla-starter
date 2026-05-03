# PHP Application Masterclass (2026) — Vanilla, Professional, Framework‑Free
**Date:** 2026-05-03  
**Goal:** Build a modern, scalable, framework‑free PHP application using best practices, clean architecture, and PSR standards.  
**Domain Example:** Pokémon catalog powered by the PokeAPI.

---

## Why Build Vanilla PHP in 2026?
Frameworks speed delivery, but hide fundamentals. Vanilla PHP forces you to understand:
- HTTP lifecycles
- Dependency injection
- Middleware pipelines
- Routing and request matching
- Configuration bootstrapping
- Security at the system level

This tutorial builds a **real template** for any professional PHP app—from micro to enterprise.

---

# ✅ Project Navigation Map (Step‑by‑Step File Path Guide)

Follow this in order. Each step tells you **exactly which file to create/edit** and what to do.

1. **Create the directory structure**  
   - Create: `/app`, `/config`, `/public`, `/resources/views`, `/storage`, `/tests`  
   - Create subfolders: `/app/Core`, `/app/Http`, `/app/Services`, `/app/Repositories`, `/resources/views/layouts`, `/resources/views/pokemon`

2. **Initialize Composer + Autoloading**  
   - Create: `/composer.json`  
   - Action: Add PSR‑4 autoload mapping `App\ => app/`  
   - Run: `composer install` and `composer dump-autoload`

3. **Environment Loader + .env**  
   - Create: `/app/Core/EnvironmentLoader.php`  
   - Create: `/.env.example`  
   - Action: Parse `.env` and store variables internally (no global pollution)

4. **Configuration Files**  
   - Create: `/config/app.php`  
   - Create: `/config/database.php`  
   - Action: Read environment values and return arrays

5. **Dependency Injection Container (PSR‑11)**  
   - Create: `/app/Core/Container.php`  
   - Create: `/app/Core/Exceptions/ContainerException.php`  
   - Create: `/app/Core/Exceptions/NotFoundException.php`  
   - Action: Resolve class dependencies using Reflection

6. **Helpers and Security Utilities**  
   - Create: `/app/Core/helpers.php`  
   - Action: Add `escape()` and `jsonResponse()` helper functions

7. **Request / Response Objects**  
   - Create: `/app/Http/Request.php`  
   - Create: `/app/Http/Response.php`  
   - Action: Build immutable request and response classes

8. **Router + Dispatching**  
   - Create: `/app/Http/Router.php`  
   - Action: Add GET route registration and regex matching for `{param}`

9. **Controller Layer**  
   - Create: `/app/Http/Controllers/PokemonController.php`  
   - Action: Read PokemonService + Renderer, return Response

10. **Service Layer (PokeAPI)**  
   - Create: `/app/Services/PokemonService.php`  
   - Action: HTTP request to `https://pokeapi.co/api/v2/pokemon/{name}`

11. **DTO Layer**  
   - Create: `/app/DTO/PokemonDTO.php`  
   - Action: Use `public private(set)` and property hooks

12. **View Renderer**  
   - Create: `/app/Core/Renderer.php`  
   - Action: Use `ob_start()` + `extract()` + layout support

13. **Views**  
   - Create: `/resources/views/layouts/main.php`  
   - Create: `/resources/views/pokemon/show.php`  
   - Action: Render Pokémon details using `escape()`

14. **Front Controller**  
   - Create: `/public/index.php`  
   - Action: Bootstrap env, config, DI container, routes, and dispatch

15. **Storage + Tests placeholders**  
   - Create: `/storage/.gitkeep`  
   - Create: `/tests/Unit/.gitkeep`  
   - Create: `/tests/Feature/.gitkeep`

---

# Phase 1 — Foundational Scaffolding (Directory Architecture)

A secure PHP app begins with **segregated structure** and a **public-only** web root.

```
/app
  /Core
  /Http
  /Services
  /Repositories
  /DTO
/config
/public
/resources/views
/storage
/tests
/vendor
```

**Purpose of each folder:**

| Path | Role |
|------|------|
| /app | Core application code |
| /app/Http | Router, controllers, middleware |
| /app/Services | Business logic + external API integration |
| /app/Repositories | Data access layer |
| /app/DTO | Data Transfer Objects |
| /config | Config arrays loaded during bootstrap |
| /public | Front controller (`index.php`) + public assets |
| /resources/views | HTML/PHP templates |
| /storage | Logs, cache, compiled artifacts |
| /tests | Unit + Feature tests |
| /vendor | Composer + 3rd‑party packages |

---

# Phase 2 — Composer + PSR‑4 Autoloading

Create `composer.json`:

```json
{
  "name": "app/pokedex",
  "autoload": {
    "psr-4": {
      "App\\": "app/"
    }
  },
  "require": {}
}
```

Run:

```bash
composer install
composer dump-autoload
```

Now `App\\Http\\Router` maps to `app/Http/Router.php`.

---

# Phase 3 — Environment Loader (Safe Config)

Create `/app/Core/EnvironmentLoader.php`

Key ideas:
- Parse `.env`
- Ignore comments
- Cast true/false/null
- Store internally (do NOT pollute `$_ENV`)

---

# Phase 4 — PSR‑11 Dependency Injection Container

Create `/app/Core/Container.php`

**Must:**
- Resolve classes via Reflection
- Cache singletons
- Inject dependencies automatically

Use PSR‑11 interfaces.

---

# Phase 5 — PSR‑7 HTTP Abstraction

Create `/app/Http/Request.php` and `/app/Http/Response.php`.

Must support:
- Immutable request objects
- Headers, URI, method, body
- Stream‑based bodies (avoid huge memory load)

---

# Phase 6 — Router + Middleware Pipeline

## Router:
- Register routes by verb
- Convert `/pokemon/{name}` to regex
- Extract params via named groups
- Return 404 or 405 when appropriate

## Middleware:
- `process(Request $req, Handler $handler)`
- Onion‑style pipeline
- Can short‑circuit responses

---

# Phase 7 — Data Persistence with PDO + Repository Pattern

Create `/app/Repositories/FavoritePokemonRepository.php`

PDO settings:

| Option | Value | Purpose |
|--------|-------|---------|
| ATTR_ERRMODE | ERRMODE_EXCEPTION | Fail loud |
| ATTR_DEFAULT_FETCH_MODE | FETCH_ASSOC | Lean arrays |
| ATTR_EMULATE_PREPARES | false | SQL injection prevention |

---

# Phase 8 — Service Layer + PokeAPI Integration

Create `/app/Services/PokemonService.php`

Responsibilities:
- HTTP calls (file_get_contents + stream_context)
- JSON decode
- Handle 404s
- Map to DTO

---

# Phase 9 — DTOs with PHP 8.4 / 8.5 Features

Create `/app/DTO/PokemonDTO.php`

Use:
- `public private(set)` for immutable state
- property hooks for computed output
- pipe operator (`|>`) to sanitize inputs

---

# Phase 10 — View Engine (Native Renderer)

Create `/app/Core/Renderer.php`

Uses:
- `extract()`
- `ob_start()`
- `ob_get_clean()`
- `startBlock()` / `endBlock()` for layouts

---

# Phase 11 — Helpers + Security Layer

Core helpers:

### `escape()`
```php
function escape(string $value): string {
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
```

### `jsonResponse(array $data, int $status = 200)`
```php
function jsonResponse(array $data, int $status = 200): void {
    http_response_code($status);
    header('Content-Type: application/json');
    echo json_encode($data, JSON_THROW_ON_ERROR);
}
```

Validation and sanitization are **separate** concerns.

---

# Phase 12 — Full Request Lifecycle (Summary)

1. HTTP hits `/public/index.php`
2. Composer autoload loads classes
3. Env loader loads `.env`
4. DI container builds services
5. Request object is created
6. Middleware pipeline runs
7. Router matches `/pokemon/pikachu`
8. Controller uses `PokemonService`
9. DTO created
10. Renderer builds HTML
11. Response returned

---

# ✅ Final Outcome

You now have a **framework‑free, scalable, professional PHP template** that:
- Handles real HTTP flows
- Uses PSR standards
- Is secure by design
- Scales from small apps to enterprise deployments
