<?php

/**
 * Bootstrap the application
 */

use Symfony\Component\Dotenv\Dotenv;


errorLog(dirname('../'));
errorLog(getcwd());
errorLog(dirname('../') . DIRECTORY_SEPARATOR . '.env');
errorLog(dirname(__DIR__, 1) . '/.env');

/**
 *  Loads in the env file for this environment
 */
try {
    $dotenv = new Dotenv();
    $dotenv->load(dirname(__DIR__, 1) . '/.env');
} catch (Exception $e) {
    // must be on heroku or an environment without an env file.
    // Heroku automatically has secrets.
    errorLog('must be on heroku or an environment without an env file.');
}

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
