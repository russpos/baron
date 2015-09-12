# baron

`baron` is a super light-weight web framework for PHP.

### What is the purpose of `baron`? 

I often make teeny tiny web apps to solve my own personal problems.

Even with tiny apps, I've found that there's plenty of duplication and 
boiler plate that would speed up my development cycle.  Usually this
consists of basic things -- routing, parsing requests, and 
delivering basic HTML or JSON responses.

After building more or less the same framework 10 times, I decided
to just copy the core-bits, clean them up, and make them sharable.

This is `baron`!

### Getting started

1. Create a new directory for your web app.  A basic structure looks like:

```
$ (~/my_app) tree
.
├── Core -> ../Core        // Symlink (or perhaps a git-submodule) to this repo
├── configuration.php      // PHP file containing "config data"
├── phplib                 // Folder where your php classes live
│   └── Controller
│       └── Index.php
├── routes.php             // PHP file with your routes
└── www                    // Your static webroot
    └── router.php         // PHP router file -- this is the main entry point to your app
```

2.  Setup your basic routes in `routes.php`.   An example router file looks like:

```
<?php

$routes = [
    "GET /"                              => "Controller_Index",
    "GET /authorize"                     => "Controller_Authorize",
    "* /api/service/(.*)"                => "Controller_Api_Service",
    "GET /api/threads/(\d+)"             => "Controller_Api_GetThread",
    "POST /assets/upload"                => "Controller_Assets_Upload",
    "POST /api/threads/(\d+)/iterations" => "Controller_Api_CreateIteration",
];
```

 Setup your application in `router.php`.  You can look at the sample file in this repo, but generally
it looks something like this:

```php
<?php

define('WWW_ROOT', dirname(__FILE__));
define('APP_ROOT', dirname(WWW_ROOT));
define('PHP_ROOT', APP_ROOT.'/phplib');
define('CORE_ROOT', APP_ROOT.'/Core');
define('CONFIG_PATH', APP_ROOT.'/configuration.php');

require APP_ROOT.'/routes.php';
require CORE_ROOT.'/Loader.php';

Router::route($routes);
```

This just tells `baron` the things it needs to know, loads your routes, and loads your routes and loads `baron`.  You can then just start the `Router` to kick off the application.

4.  For development purposes, you can use PHP's built-in server:

```
$ (~/my_app/www) php -S localhost:8000 ./router.php
```

5. This app was not really written for production purposes, so use at your own risk. For production purposes
you'll want to use a standard web-server (i.e - `nginx` or `apache`) and route all non-static requests
through `www/router.php`.

