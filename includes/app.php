<?php

use App\Utils\View;
use Dotenv\Dotenv;

require __DIR__ . "/../vendor/autoload.php";

$dotenv = Dotenv::createImmutable(__DIR__ . "/../");
$dotenv->load();

View::init([
    "URL" => $_ENV['URL'],
]);