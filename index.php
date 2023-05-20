<?php

require __DIR__ . "/includes/app.php";

use App\Http\Router;

$objRouter = new Router($_ENV['URL']);

include __DIR__ . "/routes/pages.php";
include __DIR__ . "/routes/admin.php";

$objRouter->run()->sendResponse();