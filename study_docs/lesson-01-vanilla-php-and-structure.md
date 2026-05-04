# Lesson 01: Vanilla PHP and Project Structure

**Date:** 2026-05-03  
**Course:** PHP Application Masterclass Study Track

## Learning Goals

By the end of this lesson, you should be able to:
- Define what **Vanilla PHP** means.
- Explain why we use a layered folder structure.
- Describe the purpose of each directory in this project.
- Understand why `public/` should be the only web root.

---

## 1) What is Vanilla PHP?

**Vanilla PHP** means building your application using core PHP and your own architecture, without relying on a full-stack framework like Laravel or Symfony.

You still can use Composer and external libraries. The key point is that **you control the architecture**.

### In practice, you implement:
- Routing
- Dependency injection
- Request/response flow
- Configuration loading
- Rendering
- Error handling

### Why learn this?
- You understand how frameworks work internally.
- You write cleaner, more intentional code.
- You become better at debugging and design decisions.

---

## 2) Why this structure?

The structure separates responsibilities so each part has one job.

Benefits:
- Better maintainability
- Easier testing
- Lower coupling between layers
- Safer deployment setup
- Easier onboarding for teams

This is the same architectural thinking used in professional applications, even when frameworks are present.

---

## 3) Directory structure (Phase 1)

### `app/`
Main application code.

### `app/Core/`
Core infrastructure and bootstrap utilities (container, environment loader, renderer, helpers).

### `app/Http/`
HTTP layer: request, response, router, controllers.

### `app/Services/`
Business logic and use-case orchestration.

### `app/Repositories/`
Data access layer (database/persistence concerns).

### `app/DTO/`
Data Transfer Objects for structured, typed data exchange between layers.

### `config/`
Configuration files (`app.php`, `database.php`) loaded during bootstrap.

### `public/`
Public web root. Contains `index.php` and public assets.

### `resources/views/`
View templates for presentation.

### `storage/`
Writable runtime data (logs, cache, temp artifacts).

### `tests/`
Automated tests (unit/feature).

### `vendor/`
Composer dependencies and autoload artifacts.

---

## 4) Security reason for `public/` as web root

Only `public/` should be exposed to the browser.

If the server points to project root, private files could be exposed (`.env`, `config`, source code). Keeping web access limited to `public/` protects internal code and secrets.

---

## 5) Mental model of request flow

1. Browser requests a URL.
2. Web server sends request to `public/index.php`.
3. App bootstraps environment/config/container.
4. Router matches route.
5. Controller coordinates response.
6. Service/repository fetch and prepare data.
7. View renders output.
8. Response returns to browser.

---

## Quick Check (Self-Quiz)

1. What is the difference between Vanilla PHP and a framework-based app?
2. Why is `public/` the only folder that should be web-accessible?
3. What belongs in `app/Services/` vs `app/Http/Controllers/`?
4. Why are DTOs useful in larger applications?

---

## Practice Task

Write a short paragraph for each folder below explaining what kind of code belongs there:
- `app/Http`
- `app/Services`
- `app/Repositories`
- `resources/views`

Then answer: **Which folder should never contain SQL queries, and why?**

---

## Next Lesson Preview

Next, we can cover:
- Composer + PSR-4 autoloading (`composer.json`)
- How class names map to file paths
- Why autoloading is essential for scalable PHP applications
