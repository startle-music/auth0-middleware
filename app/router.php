<?php
namespace Application;
/**
 * Application Router
 */
use Application\Controller\Login;
use Application\Controller\Callback;


$request = strtolower($_SERVER['REDIRECT_URL']);
$action = strtolower($_SERVER['REQUEST_METHOD']);

/**
 *  Switch based on route endpoint 
 */
switch ($request) {
    case '/auth/callback':
        try {
                
            $controller = new Callback();
            $controller->$$action();
        }
        break;
        
    case '/auth/':
    default:
        $controller = new Login();
        $controller->$$action();
        break;
}
