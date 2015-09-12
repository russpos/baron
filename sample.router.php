<?php

/**
 * Put this file in your "www" or other publicly accessible "root" folder
 * This assumes a folder structure like:
 * /.
 *  routes.php  // Route file containing regex map
 *  www/
 *    router.php
 *    // Your static assets
 *  phplib
 *    // Your PHP files
 *  Core
 *    // Git-submodule to this project
 */

define('WWW_ROOT', dirname(__FILE__));
define('APP_ROOT', dirname(WWW_ROOT));
define('PHP_ROOT', APP_ROOT.'/phplib');
define('CORE_ROOT', APP_ROOT.'/Core');
define('CONFIG_PATH', APP_ROOT.'/development.php');

require APP_ROOT.'/routes.php';
require CORE_ROOT.'/loader.php';

Router::route($routes);
