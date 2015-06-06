<?php

defined('APPLICATION_ENV') || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'local'));

// silex core
require_once __DIR__.'/../vendor/autoload.php';

// common config 
require_once __DIR__.'/../config/common.php';

// environement config (DBs ...)
require_once __DIR__.'/../config/'.APPLICATION_ENV.'.php';

// init service providers
require_once __DIR__.'/../src/app.php';

// define routes
require_once __DIR__.'/../src/routes.php';

// let's go
$app->run();
