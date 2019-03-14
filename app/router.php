<?php
namespace Application;
/**
 * Application Router
 */
use App\controllers\Login;
use App\controllers\Callback;

$request = strtolower($_SERVER['REQUEST_URI']);
$action = strtolower($_SERVER['REQUEST_METHOD']);

if(strpos($request, '?') !== false) {
    $request = substr($request, 0, strpos($request, '?'));
}

/**
 *  Switch based on route endpoint 
 */
switch ($request) {
    case '/auth/callback':
    case '/auth/callback/':
        try {
            $controller = new Callback();
            $controller->$action();
        } catch(Exception $e) {
            print_r('502: Method Not Allowed');
        }
        break;
        
    case '/auth/':
    default:
        try { 
            $controller = new Login();
            $controller->$action();
        } catch(Exception $e) {
            print_r('502: Method Not Allowed');
        }
        break;
}
