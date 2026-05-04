# Lesson 02: Composer and PSR-4 Autoloading

**Date:** 2026-05-03
**Course:** PHP Application Masterclass Study Track

## Learning Goals

By the end of this lesson, you should be able to:
- Explain what Composer is.
- Understand what autoloading means.
- Read the `composer.json` file in this project.
- Describe how PSR-4 maps class names to file paths.
- Run the basic Composer commands used to prepare the project.

---

## 1) What is Composer?

**Composer** is PHP's dependency manager.

It helps you:
- install third-party packages,
- manage version constraints,
- generate an autoloader,
- keep your project organized.

Instead of manually including every file with `require` or `include`, Composer can load classes for you automatically.

### Why this matters
Without Composer, larger PHP projects become difficult to manage because you must:
- track file paths manually,
- load dependencies in the right order,
- update include statements whenever files move.

Composer solves this by standardizing package management and autoloading.

---

## 2) What is autoloading?

**Autoloading** means PHP loads a class only when you use it.

Example idea:
- You write `new App\Http\Router()`
- PHP asks the autoloader where that class file lives
- Composer loads the matching file automatically

This is much cleaner than writing dozens of manual `require` statements.

---

## 3) Reading this project's `composer.json`

Your project currently has this structure in `composer.json`:

- Project name: `ignacioscript/php-vanilla-starter`
- PHP version requirement: `^8.4`
- Dependency: `psr/container`
- PSR-4 autoload rule: `App\\` maps to `app/`

### What that means
If Composer sees this class:

- `App\Core\Container`

it will look for this file:

- `app/Core/Container.php`

That mapping is the heart of PSR-4 autoloading.

---

## 4) What is PSR-4?

**PSR-4** is a naming convention that tells tools how class names map to file paths.

### Rule of thumb
- Namespace = folder path
- Class name = file name

Example:

| Class | File |
|------|------|
| `App\Http\Request` | `app/Http/Request.php` |
| `App\Http\Router` | `app/Http/Router.php` |
| `App\Services\PokemonService` | `app/Services/PokemonService.php` |

This keeps your project predictable.

---

## 5) Why autoloading is important in a real project

Autoloading helps you:
- avoid manual file imports,
- keep code modular,
- move files without rewriting the whole app,
- make testing easier,
- support clean architecture.

It also makes your codebase easier for other developers to understand.

---

## 6) Composer commands you will use

From the project root, you would usually run:

```bash
composer install
composer dump-autoload
```

### What these commands do

- `composer install`
  Installs dependencies listed in `composer.json` and creates the `vendor/` folder.

- `composer dump-autoload`
  Rebuilds Composer's autoload files after you add or move classes.

---

## 7) Where the autoloader lives

Composer generates files inside `vendor/`.

You normally do **not** edit those files manually.

In your front controller, you will later include:

```php
require __DIR__ . '/../vendor/autoload.php';
```

That single line gives your app access to all Composer-managed classes.

---

## 8) Practical example in this project

Let's say you create this file:

- `app/Http/Request.php`

with this class:

- `App\Http\Request`

Then later, in another file, you can simply write:

```php
$request = new App\Http\Request();
```

Composer will load the correct file automatically.

---

## 9) Common beginner mistakes

### Mistake 1: Namespace does not match folder path
If the class name and folder structure do not match PSR-4 rules, autoloading may fail.

### Mistake 2: Forgetting to run `composer dump-autoload`
If you add a new class and Composer does not know about it yet, regenerate the autoloader.

### Mistake 3: Mixing business logic into `vendor/`
You should never put your own application code inside `vendor/`.

---

## 10) Mental model

Think of Composer as:
- a package manager,
- an autoload generator,
- a dependency organizer.

Think of PSR-4 as:
- the map that connects class names to file paths.

---

## Quick Check (Self-Quiz)

1. What does Composer do for a PHP project?
2. What problem does autoloading solve?
3. What file path should `App\Services\PokemonService` map to?
4. Why is `vendor/` usually not edited by hand?

---

## Practice Task

Answer these questions in your own words:

1. What is the difference between `composer install` and `composer dump-autoload`?
2. Why does PSR-4 make large projects easier to maintain?
3. If a class is named `App\Core\Renderer`, where should the file live?

---

## Next Lesson Preview

Next we will study:
- the Environment Loader,
- how `.env` files work,
- why configuration should stay outside your source code.

