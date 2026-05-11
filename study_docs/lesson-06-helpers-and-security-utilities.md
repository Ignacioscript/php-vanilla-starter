# Lesson 06: Helpers and Security Utilities
**Date:** 2026-05-11  
**Course:** PHP Application Masterclass Study Track
## Learning Goals
By the end of this lesson, you should be able to:
- Explain why helper functions exist in a PHP application.
- Understand the difference between presentation helpers and response helpers.
- Read the current `app/Core/helpers.php` file.
- Explain how `escape()` helps prevent XSS attacks.
- Explain how `jsonResponse()` standardizes JSON output.
- Recognize when a helper should return a value instead of printing directly.
---
## 1) What are helpers?
Helpers are small reusable functions that solve common tasks in your application.
Instead of repeating the same logic in multiple files, you place it in one helper function and reuse it everywhere.
In this project, helpers live in:
- `app/Core/helpers.php`
That file currently contains two functions:
- `escape()`
- `jsonResponse()`
---
## 2) Why use helpers?
Helpers are useful when a piece of logic:
- is used often,
- is small and focused,
- does not really belong to a class,
- should stay easy to read.
Examples:
- escaping HTML output,
- building JSON responses,
- formatting strings,
- converting dates.
---
## 3) Reading the current `helpers.php`
Your current file contains:
```php
function escape(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
function jsonResponse(array $data, int $status = 200): Response
{
    return new Response(
        json_encode($data, JSON_THROW_ON_ERROR),
        $status,
        ['Content-Type' => 'application/json']
    );
}
```
### What this means
- `escape()` prepares text for safe HTML output.
- `jsonResponse()` builds a `Response` object with JSON content.
---
## 4) `escape()` and XSS protection
XSS stands for **Cross-Site Scripting**.
It happens when unsafe user input is printed into HTML without escaping.
Example dangerous input:
```php
<script>alert('hack')</script>
```
If you print that directly into a page, the browser may treat it as real JavaScript.
### What `escape()` does
`escape()` converts special characters into HTML entities:
- `<` becomes `&lt;`
- `>` becomes `&gt;`
- `"` becomes `&quot;`
- `'` becomes `&#039;`
So the browser displays the text instead of executing it.
### Why the flags matter
Your code uses:
```php
htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8')
```
- `ENT_QUOTES` escapes both single and double quotes.
- `ENT_SUBSTITUTE` replaces invalid UTF-8 sequences safely.
- `'UTF-8'` tells PHP which encoding to use.
This is the correct kind of protection for rendering user content in HTML.
---
## 5) `jsonResponse()` and API output
JSON is a common format for APIs.
Instead of manually setting headers and encoding arrays in multiple controllers, a helper can standardize the response.
Your current implementation returns a `Response` object:
- body contains `json_encode($data, JSON_THROW_ON_ERROR)`
- status code is configurable
- header `Content-Type: application/json` is included
### Why this is good
- consistent API responses,
- easier to test,
- less repeated code,
- controllers stay small.
---
## 6) Important design idea: return values vs. side effects
A helper can either:
- **return a value**, or
- **perform a side effect**.
### `escape()`
Returns a string. No side effects.
### `jsonResponse()`
In your current project, it returns a `Response` object instead of directly echoing output.
That is a clean design because:
- the controller or front controller can decide when to send it,
- the helper stays reusable,
- the app architecture stays consistent.
This is different from a helper that immediately calls `header()` and `echo`.
---
## 7) Why helpers should stay small
Helpers should be:
- simple,
- readable,
- predictable,
- easy to test.
If a helper becomes too large or too complex, it may belong in a class or service instead.
### Good helper example
- escaping HTML output
### Better as a class/service
- database querying
- complex business rules
- large stateful workflows
---
## 8) Where helpers fit in the app flow
Typical flow:
1. Request enters the app.
2. Controller gets data from service.
3. View prints data using `escape()`.
4. API endpoint returns a JSON `Response` using `jsonResponse()`.
So helpers are part of both:
- the presentation layer,
- the response layer.
---
## 9) Common mistakes with helpers
### Mistake 1: Printing raw user input
Never output user input directly into HTML.
Use `escape()`.
### Mistake 2: Mixing too much logic into a helper
Helpers should stay small.
If logic grows too much, move it elsewhere.
### Mistake 3: Making helpers depend on hidden global state
The best helpers are explicit and predictable.
### Mistake 4: Treating JSON like HTML
JSON responses need the correct header.
Otherwise clients may interpret data incorrectly.
---
## 10) Mental model
- `escape()` protects HTML output.
- `jsonResponse()` standardizes JSON API output.
- Helpers reduce repetition.
- Good helpers stay tiny and focused.
---
## Quick Check (Self-Quiz)
1. Why do we escape output before putting it into HTML?
2. What kind of attack does `escape()` help prevent?
3. Why is a JSON `Content-Type` header important?
4. Why should helpers remain small and focused?
5. What is the difference between returning a `Response` and directly echoing output?
---
## Practice Task
1. Add a helper called `old()` that returns a default string if a value is empty.
2. Explain why that helper should still be simple.
3. Update one sample view to use `escape()` for a Pokémon name.
4. Describe what could happen if you forget to escape a user-provided value.
---
## Next Lesson Preview
Next we can cover:
- Request and Response objects,
- why HTTP abstractions should be immutable,
- and how stream-based bodies help keep memory usage under control.
