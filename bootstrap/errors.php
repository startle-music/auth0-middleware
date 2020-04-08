<?php


function errorLog($log = '')
{
    if (isset($_GET['adebug'])) {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
        error_log($log);
        print_r('<pre>');
        print_r($log);
        print_r('</pre>');
    }
}
