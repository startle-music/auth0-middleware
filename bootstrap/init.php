<?php
/**
 * Bootstrap the application
 */
use Symfony\Component\Dotenv\Dotenv;


/**
 *  Loads in the env file for this environment
 */
$dotenv = new Dotenv();
$dotenv->load(__DIR__.'/.env');