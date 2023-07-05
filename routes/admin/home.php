<?php

use App\Http\Response;
use App\Controller\Admin;

$objRouter->get("/admin", [
    "middlewares" => [
        "RequireAdminLogin"
    ],
    function($request) {
        return new Response(200, Admin\Home::getHome($request));
    }
]);