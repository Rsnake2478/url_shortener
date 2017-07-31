<?php

require_once __DIR__ . '/../vendor/autoload.php';

$app = new \Silex\Application();

// Apply configuration
require_once  __DIR__ . '/../app/config/config.php';

// Initialize services
require_once __DIR__ . '/../app/services.php';

// Initialize routes
require_once __DIR__ . '/../app/routing.php';

$app->run();
