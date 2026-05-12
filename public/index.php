<?php

error_reporting(E_ALL);

require_once __DIR__ . '/../config/app.php';
require_once APPLICATION . '/vendor/autoload.php';

use \app\Container\Container;

$container = Container::instance(require_once APPLICATION . '/app/Container/Dependencies.php');

$request = $container->get(\app\Http\Request::class);
$router = $container->get(\app\Http\Router::class);

$actionData = $router->getActionData($request->getUri());

try {
    if (!empty($actionData)) {
        $controller = $container->get($actionData['controller']);
        $action = $actionData['action'];

        $controller->$action();
    } else {
        include '../resources/views/errors/404.php';
    }
} catch (PDOException $e) {
    $error_info = getErrorInfo($e, 'Something went wrong with PDO connection');
    include '../resources/views/errors/error.php';
} catch (\Exception $e) {
    $error_info = getErrorInfo($e, 'Something went wrong');
    include '../resources/views/errors/error.php';
}

function getErrorInfo($e, $title) {
    $error_info['title'] = $title;
    $error_info['file'] = $e->getFile();
    $error_info['line'] = $e->getLine();
    $error_info['msg'] = $e->getMessage();
    return $error_info;
}
