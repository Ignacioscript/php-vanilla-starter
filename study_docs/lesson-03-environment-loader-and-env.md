# Lesson 03: Environment Loader and .env

**Date:** 2026-05-03
**Course:** PHP Application Masterclass Study Track

---

## Learning Goals
By the end of this lesson, you should be able to:
* Explain why `.env` files exist.
* Understand what an environment loader does.
* Read the current `EnvironmentLoader.php` implementation.
* Explain the purpose of `.env.example`.
* Identify which settings belong in environment files.

---

## 1) What is a .env file?
A `.env` file is a simple text file used to store environment-specific configuration. It usually contains values like:
* **Application environment** (local, production)
* **Debug mode** (true or false)
* **API base URLs**
* **Secret keys**

### Why use it?
Configuration should be separated from source code. This means:
* The same code can run in different environments.
* Secrets do not need to be hard-coded.
* Deployments become safer and easier to manage.

---

## 2) What is an Environment Loader?
An environment loader is a small class that reads a `.env` file and stores the values internally. In this project, the loader:
1. Reads the file line by line.
2. Ignores empty lines and comments (starting with `#`).
3. Splits valid lines into `KEY=VALUE`.
4. **Casts** common values like `true`, `false`, and `null`.
5. Keeps data inside the object to avoid polluting global state.

> **Note:** Storing variables in a private array instead of writing directly to `$_ENV` keeps the global environment "clean."

---

## 3) Reading the current EnvironmentLoader.php
**Location:** `app/Core/EnvironmentLoader.php`

### What it does well:
It uses standard PHP functions to keep logic simple:
* `file()`: To read the file.
* `trim()`: To clean whitespace.
* `str_starts_with()`: To skip comments.
* `explode('=', $line, 2)`: To split keys and values.
* `cast()`: A custom method to convert strings into real PHP types.

### Internal Storage:
The class stores parsed values in:
`private array $vars = [];`
This makes the object a "container" for your configuration.

---

## 4) What cast() means
The `cast()` method turns text strings into useful PHP types:
* `"true"` $\rightarrow$ `true` (boolean)
* `"false"` $\rightarrow$ `false` (boolean)
* `"null"` $\rightarrow$ `null`
* Quoted strings like `"hello"` $\rightarrow$ `hello`

This is helpful because `.env` files are plain text, but your code needs real booleans and nulls for logic.

---

## 5) What belongs in .env.example?
The `.env.example` file is a **template**. It shows which variables the app expects without exposing real secrets.

### Why have an example file?
So every developer knows:
* Which values are **required**.
* How to **name** them.
* What **kind** of data they should contain.

**Crucial Habit:** The real `.env` file should **never** be committed to Git.

---

## 6) Understanding the example variables
* **APP_ENV=local**: Defines the environment (local, testing, production).
* **APP_DEBUG=true**: Turns debug features on/off (true in dev, false in prod).
* **APP_URL=http://localhost:8000**: Base URL for generating links and paths.
* **Database Settings**: `DB_DRIVER`, `DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER`, `DB_PASS`.
* **POKEAPI_BASE_URL**: Keeps external API endpoints configurable.

---

## 7) Why configuration should not live in code
Hard-coding credentials leads to:
* Annoying environment changes.
* Exposure of secrets (security risk).
* Difficulty in reusing code.

---

## 8) How the loader and .env.example work together
1. **.env.example** = The Blueprint.
2. **.env** = The Actual Values (Private).
3. **EnvironmentLoader** = The Reader.

**Workflow:**
* Copy `.env.example` to `.env`.
* Fill in real values.
* Load with `EnvironmentLoader`.

---

## 9) Important habits when working with .env

| **Do** | **Do Not** |
| :--- | :--- |
| Keep secrets out of source code. | Commit real passwords or keys. |
| Commit `.env.example`. | Use `.env` for application logic. |
| Use descriptive names. | Store complex data structures in one line. |

---

## 10) Current limitations
This loader is intentionally simple. It does **not** handle:
* Inline comments.
* `export KEY=value` syntax.
* Multiline values or escaped equals signs.

---

## 11) Mental Model

* **.env.example** tells you what the app expects.
* **.env** stores your real values.
* **EnvironmentLoader** reads those values safely.

---

## Quick Check (Self-Quiz)
1. Why should real secrets not be stored directly in PHP source code?
2. What is the difference between `.env` and `.env.example`?
3. Why does the loader cast values like `true` and `false`?
4. What does it mean to keep environment variables "internally"?

---

## Practice Task
* Which variables in `.env.example` are related to the application itself?
* Which variables are related to the database?
* Why is `POKEAPI_BASE_URL` a good candidate for a `.env` value?
* What could go wrong if `APP_DEBUG=true` stays enabled in production?

---

## Next Lesson Preview
* Configuration files in `config/`.
* Turning environment values into app settings.
* Why config files are the bridge between `.env` and the rest of the app.