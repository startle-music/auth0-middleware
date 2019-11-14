<?php


function errorLog($nolog = '')
{ }

if (isset($_GET['adebug'])) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);


    function errorLog($log = '')
    {
        error_log($log);
    }
}
