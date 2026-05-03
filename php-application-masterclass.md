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

# ✅ Enumerated Build Steps (What to Create, In Order)

1. **Create the directory structure** (Phase 1).  
2. **Initialize Composer** + PSR‑4 autoloading (Phase 2).  
3. **Build the Environment Loader** and `.env` (Phase 3).  
4. **Implement the PSR‑11 DI Container** (Phase 4).  
5. **Create PSR‑7 style Request/Response** classes (Phase 5).  
6. **Build the Router + route matching** (Phase 6).  
7. **Add Middleware pipeline** (Phase 6).  
8. **Configure PDO + Repository layer** (Phase 7).  
9. **Add Service layer + PokeAPI client** (Phase 8).  
10. **Create DTOs with PHP 8.4/8.5 features** (Phase 9).  
11. **Build the Renderer + Layout engine** (Phase 10).  
12. **Add helper functions + security** (Phase 11).  
13. **Walk through full request lifecycle** (Phase 12).  

---

# Phase 1 — Foundational Scaffolding (Directory Architecture)

A secure PHP app begins with **segregated structure** and a **public-only** web root.

```
/app
  /Core
  /Http
  /Services
  /Repositories
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
