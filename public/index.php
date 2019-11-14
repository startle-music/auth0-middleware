<?php

/**
 *  Set up Error Logging
 */
require_once('../bootstrap/errors.php');

/**
 *  Autoload all classes installed by composer
 * 
 */
errorLog('Boot Autoloader');
require_once('../vendor/autoload.php');

/**
 *  Bootstrap#
 * 
 */
errorLog('Boot Bootstrap');
require_once('../bootstrap/init.php');


/**
 *  App start
 * 
 */
errorLog('App Start');
require_once('../app/init.php');
