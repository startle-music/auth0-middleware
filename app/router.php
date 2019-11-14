<?php

namespace Application;

/**
 * Application Router
 */

use App\controllers\Login;
use App\controllers\Logout;
use App\controllers\Callback;
use App\controllers\HealthCheck;

$request = strtolower($_SERVER['REQUEST_URI']);
$action = strtolower($_SERVER['REQUEST_METHOD']);

if (strpos($request, '?') !== false) {
    $request = substr($request, 0, strpos($request, '?'));
}

/**
 *  Switch based on route endpoint 
 */
switch ($request) {
    case '/healthcheck/':
        try {
            $controller = new HealthCheck();
            $controller->$action();
        } catch (Exception $e) {
            print_r('502: Method Not Allowed');
        }
        break;


    case '/auth/callback':
    case '/auth/callback/':
        try {
            $controller = new Callback();
            $controller->$action();
        } catch (Exception $e) {
            print_r('502: Method Not Allowed');
        }
        break;

    case '/auth/logout':
    case '/auth/logout/':
        try {
            $controller = new Logout();
            $controller->$action();
        } catch (Exception $e) {
            print_r('502: Method Not Allowed');
        }
        break;

    case '/auth/':
    case '/':
    default:
        try {
            $controller = new Login();
            if ($controller->middleware()) {
                $controller->$action();
            } else {
                print_r('You have successfully logged out');
            }
        } catch (Exception $e) {
            print_r('502: Method Not Allowed');
        }
        break;
}
