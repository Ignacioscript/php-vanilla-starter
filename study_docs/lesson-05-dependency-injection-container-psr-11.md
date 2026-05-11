# Lesson 05: Dependency Injection Container (PSR-11)

**Date:** 2026-05-04
**Course:** PHP Application Masterclass Study Track

## Learning Goals

By the end of this lesson, you should be able to:
- Explain what Dependency Injection (DI) is.
- Understand why a Container solves dependency management.
- Know what PSR-11 is and why it matters.
- Read your `Container.php` implementation.
- Trace class resolution via Reflection.
- Use singleton caching and manual bindings correctly.

---

## 1) The Dependency Problem

### Without DI: tight coupling
```php
class UserService {
    public function __construct() {
        $this->db = new Database();
        $this->logger = new Logger();
    }
}
```

Problems:
- hard to test,
- hard to replace implementations,
- hidden dependencies.

### With DI: loose coupling
```php
class UserService {
    public function __construct(DatabaseInterface $db, LoggerInterface $logger) {
        $this->db = $db;
        $this->logger = $logger;
    }
}
```

Benefits:
- explicit dependencies,
- easier testing,
- easier refactoring.

---

## 2) What is a Service Container?

A Service Container (IoC Container) is an object that:
- stores service recipes,
- builds services,
- resolves nested dependencies,
- returns reusable instances.

Think of it as a smart factory for your application objects.

---

## 3) What is PSR-11?

PSR-11 is a standard interface for PHP containers.

It defines:
- `get(string $id): mixed`
- `has(string $id): bool`

And exception contracts:
- `ContainerExceptionInterface`
- `NotFoundExceptionInterface`

Why it matters: your app can depend on the standard interface instead of one specific container implementation.

---

## 4) Your `Container.php` Structure

From your project:
- `$bindings`: recipes (what to build)
- `$instances`: cache (already built services)

Public methods:
- `set()` registers recipes
- `get()` resolves/builds services
- `has()` checks if an ID is resolvable

---

## 5) `set()` Method

```php
public function set(string $id, mixed $concrete): void
{
    $this->bindings[$id] = $concrete;
}
```

Use it to register:
- plain values,
- closures/factories,
- class mappings.

Example:
```php
$container->set('config', $configArray);
$container->set('db', function ($c) {
    return new PDO('mysql:host=127.0.0.1;dbname=pokedex', 'root', '');
});
```

---

## 6) `has()` Method

```php
public function has(string $id): bool
{
    return isset($this->instances[$id])
        || array_key_exists($id, $this->bindings)
        || class_exists($id);
}
```

It returns true if:
- service already exists in cache,
- service has a binding,
- class exists and can be auto-resolved.

---

## 7) `get()` Resolution Flow

### Step A: return cached instance
```php
if (isset($this->instances[$id])) {
    return $this->instances[$id];
}
```

### Step B: resolve from bindings
```php
if (array_key_exists($id, $this->bindings)) {
    $concrete = $this->bindings[$id];
    $object = is_callable($concrete) ? $concrete($this) : $concrete;
    $this->instances[$id] = $object;
    return $object;
}
```

### Step C: auto-resolve class by Reflection
If no binding exists and class does not exist -> throw `NotFoundException`.

If class exists, inspect constructor and recursively resolve each typed parameter.

---

## 8) Reflection and Auto-Wiring

Reflection lets PHP inspect classes at runtime.

Your container uses:
- `ReflectionClass($id)`
- `$reflection->getConstructor()`
- `$constructor->getParameters()`

Then for each parameter:
1. read type hint,
2. call `$this->get($type->getName())`,
3. collect dependencies,
4. instantiate class with `newInstanceArgs()`.

This is why typed constructor injection is so powerful.

---

## 9) Exception Layer

### `ContainerException`
Thrown when dependency resolution fails (e.g., untyped constructor parameter).

### `NotFoundException`
Thrown when requested ID is not bound and class does not exist.

Both match PSR-11 exception interfaces.

---

## 10) Singleton Caching Behavior

Your container caches every resolved service in `$instances`.

Implications:
- same ID returns same object for the request lifecycle,
- avoids rebuilding expensive services,
- shared dependencies stay consistent.

---

## 11) Practical Usage Patterns

### A) Factory binding
```php
$container->set(PDO::class, function () {
    return new PDO('mysql:host=127.0.0.1;dbname=pokedex', 'root', '');
});
```

### B) Class auto-resolution
```php
$service = $container->get(App\Services\PokemonService::class);
```

### C) Testing override
```php
$container->set(DatabaseInterface::class, new FakeDatabase());
```

---

## 12) Mental Model

- Registration phase: define bindings.
- Resolution phase: call `get()`.
- Container recursively builds the graph.
- Built instances are cached and reused.

That is DI in action.

---

## Quick Check (Self-Quiz)

1. Why is DI better than `new` inside services?
2. What does PSR-11 standardize?
3. What is the difference between `$bindings` and `$instances`?
4. Why does auto-resolution fail for untyped constructor params?
5. Why does singleton caching help performance?

---

## Practice Task

Given:
```php
class Repository {
    public function __construct(PDO $pdo) {}
}

class Service {
    public function __construct(Repository $repo) {}
}
```

1. Register `PDO` using a closure binding.
2. Resolve `Service` with `$container->get(Service::class)`.
3. Confirm whether `Repository` is built once or twice when calling `get(Service::class)` twice.

---

## Next Lesson Preview

Next lesson: Helpers and Security Utilities (`app/Core/helpers.php`)
- `escape()` and XSS prevention,
- `jsonResponse()` for API output,
- where helpers belong in clean architecture.
