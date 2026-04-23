<?php

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

require_once __DIR__ . '/Http/Route.php';
require_once __DIR__ . '/Http/Router.php';
require_once __DIR__ . '/Http/Controller.php';

require_once __DIR__ . '/View/View.php';

require_once __DIR__ . '/Database/Database.php';

require_once __DIR__ . '/ErrorHandler/ErrorHandler.php';

require_once __DIR__ . '/Exceptions/HttpException.php';

require_once __DIR__ . '/Middleware/Middleware.php';
require_once __DIR__ . '/Middleware/AuthMiddleware.php';

require_once __DIR__ . '/helpers.php';
