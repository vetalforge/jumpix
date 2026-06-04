# Jumpix

Jumpix is a lightweight PHP framework package for building small MVC applications. It provides the reusable framework core: routing, HTTP request/session helpers, a PSR-compatible dependency injection container, database query tools, ORM abstractions, and view rendering utilities.

For a ready-to-run application structure, use [vetalforge/jumpix-app](https://packagist.org/packages/vetalforge/jumpix-app).

## Installation

Install the framework package with Composer:

```bash
composer require vetalforge/jumpix
```

## Requirements

- PHP 8.1 or higher
- Composer
- PDO extension

Optional integrations:

```bash
composer require smarty/smarty
composer require doctrine/orm symfony/cache
```

## Namespace

Framework classes use the `Jumpix\` namespace:

```php
use Jumpix\Container\Container;
use Jumpix\Http\Request;
use Jumpix\Http\Router;
use Jumpix\Http\Session;
use Jumpix\Core\Database\QueryBuilder;
use Jumpix\Models\Model;
use Jumpix\Views\TemplateEngine;
```

Your application code should use its own namespace, usually `App\`.

## What Is Included

```text
src/
  Container/   Dependency injection container
  Core/
    Database/  Query builder and database seeder
    ORM/       Repository interfaces and native/Doctrine drivers
  Http/        Request, router, and session helpers
  Models/      Base Active Record model
  Views/       Native, Smarty, and template engine renderers
```

## Basic Router Usage

```php
use Jumpix\Http\Router;

$router = new Router([
    '/' => [
        'controller' => App\Controllers\HomeController::class,
        'action' => 'index',
    ],
]);

$actionData = $router->getActionData('/');
```

## Container Usage

```php
use Jumpix\Container\Container;
use Jumpix\Http\Request;

$container = new Container([
    Request::class => fn () => new Request(false, '/my-app'),
]);

$request = $container->get(Request::class);
```

## Native Model Usage

Create application models by extending the framework base model:

```php
namespace App\Models;

use Jumpix\Models\Model;

class User extends Model
{
    protected string $table = 'users';
}
```

Before using native models, provide a configured query builder:

```php
use Jumpix\Core\Database\QueryBuilder;
use Jumpix\Models\Model;

$pdo = new PDO('mysql:host=localhost;dbname=app;charset=UTF8', 'root', '');
Model::setBuilder(new QueryBuilder($pdo));
```

Then use the Active Record API:

```php
use App\Models\User;

$users = User::all();
$user = User::find(1);

$user = User::create([
    'name' => 'John Doe',
    'email' => 'john@example.test',
]);

$user->name = 'John Updated';
$user->save();
$user->delete();
```

## Template Engine

```php
use Jumpix\Views\TemplateEngine;

$views = new TemplateEngine(__DIR__ . '/resources/views');

echo $views->render('home', [
    'title' => 'Welcome',
    'items' => ['One', 'Two', 'Three'],
]);
```

Example template:

```html
<h1>{{ title }}</h1>

<ul>
    @foreach($items as $item)
    <li>{{ item }}</li>
    @endforeach
</ul>
```

## Database Seeder

```php
use Jumpix\Core\Database\DatabaseSeeder;

$seeder = new DatabaseSeeder($pdo, __DIR__ . '/database');
$seeder->run([
    'create_tables.sql',
    'create_users.sql',
]);
```

## Starter App

The recommended way to start a new project is:

```bash
composer create-project vetalforge/jumpix-app my-app
```

The app skeleton contains `public/`, `config/`, `resources/`, controllers, routes, examples, and DI bindings.

## License

Jumpix is open-sourced software licensed under the MIT license.