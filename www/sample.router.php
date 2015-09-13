<?php

/**
 * Put this file in your "www" or other publicly accessible "root" folder
 * This assumes a folder structure like:
 * /.
 *  routes.php  // Route file containing regex map
 *  www/
 *    router.php
 *    // Your static assets
 *  somefolder/
 *    // Your PHP files
 *  someotherfolder/
 *    // Git-submodule to this project
 */

// 1. Require your config file
require dirname(__FILE__).'../sample.config.php';

// 2. Require the loader.
require CORE_ROOT.'/loader.php';

// 3. Pass routes into the Router to kick off the app
// Routes can either be in another file, or defined right here in router.
// Doesn't really make much of a difference
require APP_ROOT.'/routes.php';
Router::route($routes);
