# Jumpix

Jumpix is a minimalistic PHP framework based on the **MVC** architecture.  It focuses on simplicity, flexibility, and convenient web application development.

**Key Features**
- Custom router for URL handling
- Dependency injection (DI) container
- Native Active Record ORM for database interactions
- Doctrine ORM support through a switchable repository layer
- Template Engine with conditionals and loops
- HTTP request and session management
- Error handling and custom error pages

## System Requirements
- PHP >= 8.0
- MySQL or MariaDB
- Apache 2
- Composer

## Installation
Clone the repository:
```bash
git clone https://github.com/your-username/your-repository.git
```

Navigate to the project directory:

```bash
cd your-repository
```

Configure the application by editing the config/app.php file:
```php
<?php

require_once __DIR__ . '/env_helper.php';

$appPath = env_value('APP_PATH', dirname(__DIR__));
$appPath = rtrim($appPath, '/\\') . DIRECTORY_SEPARATOR;

define('APPLICATION', $appPath);
define('HOST', env_value('HOST', 'http://localhost'));
define('DOMAIN_SYM', env_bool('DOMAIN_SYM', false));
define('DOMAIN_ADDITION', env_value('DOMAIN_ADDITION', '/framework'));

define('DB_HOST', env_value('DB_HOST', 'localhost'));
define('DB_USER', env_value('DB_USER', 'root'));
define('DB_PASS', env_value('DB_PASS', ''));
define('DB_NAME', env_value('DB_NAME', 'framework_test'));

define('ORM_DRIVER', env_value('ORM_DRIVER', 'native'));
```

Install dependencies with Composer:

```bash
composer install
```

Composer installs the framework dependencies, including Doctrine ORM and Symfony Cache for the Doctrine driver.

## Project Structure
```bash
app/
  Container/
  Controllers/
  Core/
    Database/
    ORM/
  Entities/
  Http/
  Models/
  Views/
config/
  app.php
  env_helper.php
  routes.php
database/
public/
  assets/
  index.php
resources/
  assets/
  views/
vendor/
```

## Entry Point
The main entry point of the application is public/index.php.

## Routes are matched

Corresponding controllers are executed

## Routing
Routes are defined in config/routes.php:
```php
<?php

use App\Controllers\MainPageController;
use App\Controllers\DemoController;

return [
    '/' => [
        'controller' => MainPageController::class,
        'action' => 'index'
    ],
    '/usage' => [
        'controller' => DemoController::class,
        'action' => 'index'
    ],
    '/usage/model-test' => [
        'controller' => DemoController::class,
        'action' => 'modelTest'
    ],
    '/usage/doctrine-model-test' => [
        'controller' => DemoController::class,
        'action' => 'doctrineModelTest'
    ],
];
```

## Controllers
Controllers must extend the base Controller class:
```php
<?php

namespace App\Controllers;

use App\Core\ORM\ORM;
use App\Http\{Request, Session};

class DemoController extends Controller
{
    private ORM $orm;

    public function __construct(Request $request, Session $session, ORM $orm)
    {
        parent::__construct($request, $session);
        $this->orm = $orm;
    }

    public function index()
    {
        $url = ['home' => HOST . DOMAIN_ADDITION];
        $this->render('header', $url);
        $this->render('demo', $url);
        $this->render('footer');
    }
}
```

## Models and ORM
Jumpix includes two ORM paths:

- Native Active Record models in `app/Models`
- Doctrine entities in `app/Entities`

The native ORM keeps the simple Active Record API:

```php
<?php

namespace App\Models;

class User extends Model
{
    protected string $table = 'users';
}
```

```php
use App\Models\User;

$users = User::all();
$user = User::find(1);

$created = User::create([
    'name' => 'Model Test',
    'email' => 'model-test@example.test',
]);

$created->name = 'Model Test Updated';
$created->save();
$created->delete();
```

Doctrine support is available through the repository layer. The active driver is configured with `ORM_DRIVER`:

```php
define('ORM_DRIVER', env_value('ORM_DRIVER', 'native'));
```

Use `native` for the built-in ORM:

```env
ORM_DRIVER=native
```

Use `doctrine` for Doctrine ORM:

```env
ORM_DRIVER=doctrine
```

Doctrine entities are stored in `app/Entities`. The framework includes an example `App\Entities\User` entity mapped to the `users` table.

```php
$users = $orm->repository(\App\Entities\User::class);

$user = $users->find(1);

$created = \App\Entities\User::create(
    'Doctrine Model Test',
    'doctrine-model-test@example.test',
    password_hash('secret', PASSWORD_DEFAULT)
);

$users->save($created);
$users->delete($created);
```

Demo pages:

- `/usage/model-test` tests the native Active Record model.
- `/usage/doctrine-model-test` tests the Doctrine entity through the repository layer.

## Template Engine
Jumpix also includes a template engine supporting variables, loops, and conditionals.

Example template resources/views/test.php:
```html
<h1>{{ title }}</h1>
<p>{{ text1 }}</p>

@if($user)
<p>Welcome, {{ user }}</p>
@else
<p>Please log in.</p>
@endif

<ul>
    @foreach($items as $item)
    <li>{{ item }}</li>
    @endforeach
</ul>
```

## Dependency Injection
All classes that should be injected are registered inside app/Container/Dependencies.php.

## Error Handling
Custom error pages are supported, including a styled 404 Page Not Found.

## License
MIT License.
