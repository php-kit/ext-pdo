# Ext-PDO

Ext-PDO is a lightweight extension to PHP's native PDO API that ships sensible defaults for the most popular database drivers and adds a few helper methods to streamline everyday database work.

## Features

- **Driver-aware factory** – `ExtPDO::create()` instantiates the appropriate subclass for MySQL, PostgreSQL, SQLite or SQL Server, so you only need to provide driver-agnostic connection settings.
- **Safer transaction handling** – transparent tracking of the transaction depth prevents accidental commits when you are using nested transactions and only calls through to PDO when the outermost transaction succeeds.
- **Convenience helpers** – `exec()`, `select()` and `get()` accept bound parameters directly and return useful values without requiring boilerplate prepared-statement code.
- **Optimized connection defaults** – the bundled driver subclasses configure error modes, fetch modes, timeouts and driver-specific options like ANSI quotes for MySQL so you do not have to remember to tune them yourself.
- **Environment-driven configuration (optional)** – the `Connection` and `Connections` helpers can bootstrap connections from environment variables or custom factories, making it easy to centralize connection management in larger applications.

## Benefits

- Reduce boilerplate when running parameterized queries and fetching results thanks to helper methods.
- Gain predictable error handling and data fetching behaviour from the sensible defaults applied to each driver.
- Safely compose higher-level data access routines that require nested transactions.
- Standardize how connections are created across environments while keeping the flexibility to override options when necessary.

## Installation

Install the package via Composer:

```bash
composer require php-kit/ext-pdo
```

## Usage

### Creating a connection directly

```php
use PhpKit\ExtPDO\ExtPDO;

$pdo = ExtPDO::create('mysql', [
    'host' => '127.0.0.1',
    'database' => 'app',
    'username' => 'app_user',
    'password' => 'secret',
    'charset' => 'utf8mb4',
]);

$pdo->beginTransaction();

try {
    // Run parameterized statements without manual statement preparation.
    $pdo->exec('INSERT INTO posts (title, body) VALUES (?, ?)', ['Hello', 'First post']);

    // Quickly fetch a single value.
    $postCount = $pdo->get('SELECT COUNT(*) FROM posts');

    // Or work with a full result set.
    $statement = $pdo->select('SELECT * FROM posts WHERE id = ?', [1]);
    $post = $statement->fetch();

    $pdo->commit();
} catch (Throwable $e) {
    $pdo->rollBack();
    throw $e;
}
```

You can override any PDO options at creation time by passing an `$optionsOverride` array as the third argument to `ExtPDO::create()`.

### Loading connections from the environment

```php
use PhpKit\ExtPDO\Connections;

$connections = new Connections();

// Reads DB_DRIVER, DB_HOST, DB_DATABASE, DB_USERNAME, etc. from the environment.
$default = $connections->get();

// Register additional named connections when needed.
$connections->register('reporting', function () {
    // Build and return a Connection instance here.
});
```

Set environment variables such as `DB_DRIVER`, `DB_HOST`, `DB_DATABASE`, `DB_USERNAME`, and `DB_PASSWORD` (or their `NAME_`-prefixed variants for named connections) to let Ext-PDO configure the connection automatically.

---

Copyright &copy; 2015 Impactwave, Lda.
