<?php

namespace Application;

/**
 * Application Router
 */

use App\controllers\Login;
use App\RouteTable;

$route = RouteTable::resolve($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);

try {
    $controllerClass = $route['controller'];
    $action = $route['action'];
    $controller = new $controllerClass();

    if ($controller instanceof Login) {
        if ($controller->middleware()) {
            $controller->$action();
        } else {
            print_r('You have successfully logged out');
        }
    } else {
        $controller->$action();
    }
} catch (\Exception $e) {
    print_r('502: Method Not Allowed');
}
