<?php
namespace Application;
/**
 * Application Router
 */
use Application\Controller\Login;
use Application\Controller\Callback;


$request = $_SERVER['REDIRECT_URL'];

switch ($request) {
    case '/callback' :
        break;
        
    case '/' :
    default:
        break;
}
