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

require dirname(__FILE__).'/sample.config.php';
require CORE_ROOT.'/loader.php';

require APP_ROOT.'/routes.php';
Router::route($routes);
