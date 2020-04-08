<?php

/**
 * Bootstrap the application
 */

use Symfony\Component\Dotenv\Dotenv;

/**
 *  Loads in the env file for this environment
 */
try {
    $dotenv = new Dotenv();
    errorLog(dirname('../') . DIRECTORY_SEPARATOR . '.env');
    $dotenv->load(dirname('../') . DIRECTORY_SEPARATOR . '.env');
} catch (Exception $e) {
    // must be on heroku or an environment without an env file.
    // Heroku automatically has secrets.
    errorLog('must be on heroku or an environment without an env file.');
}

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
