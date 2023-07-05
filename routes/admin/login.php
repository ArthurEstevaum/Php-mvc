<?php

use App\Http\Request;
use App\Http\Response;
use App\Controller\Admin;

//Formulário de Login

$objRouter->get("/admin/login", [
    "middlewares" => [
        "RequireAdminLogout"
    ],
    function(Request $request) {
        return new Response(200, Admin\Login::getLogin($request));
    }
]);

//Rota de logout

$objRouter->get("/admin/logout", [
    "middlewares" => [
        "RequireAdminLogin"
    ],
    function(Request $request) {
        return new Response(200, Admin\Login::setLogout($request));
    }
]);

//Rota de autenticação de login

$objRouter->post("/admin/login", [
    "middlewares" => [
        "RequireAdminLogout"
    ],
    function(Request $request) {
        return new Response(200, Admin\Login::authenticateLogin($request));
    }
]);