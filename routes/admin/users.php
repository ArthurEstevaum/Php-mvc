<?php

use App\Http\Response;
use App\Controller\Admin;
use App\Http\Request;

//Rota de listagem de usuários
$objRouter->get('/admin/users', [
    "middlewares" => [
        "RequireAdminLogin"
    ],
    function(Request $request) {
        return new Response(200, Admin\User::getUsers($request));
    }
]);

//Rota de formulário de cadastro de usuários
$objRouter->get("/admin/users/new", [
    "middlewares" => [
        "RequireAdminLogin"
    ],
    function(Request $request) {
        return new Response(200, Admin\User::getNewUser($request));
    }
]);

//Rota de edição de usuários

$objRouter->get("/admin/users/{id}/edit", [
    "middlewares" => [
        "RequireAdminLogin"
    ],
    function (Request $request, $id) {
        return new Response(200, Admin\User::getEditedUser($request, $id));
    }
]);

//Rota de cadastro de usuários (post)
$objRouter->post('/admin/users/new', [
    "middlewares" => [
        "RequireAdminLogin"
    ],
    function(Request $request) {
        return new Response(201 , Admin\User::insertUser($request));
    }
]);

//Rota de edição de usuários (put)
$objRouter->post("/admin/users/{id}/edit", [
    "middlewares" => [
        "RequireAdminLogin"
    ],
    function(Request $request, int $id) {
        return new Response(200, Admin\User::updateUser($request, $id));
    }
]);

$objRouter->get("/admin/users/{id}/delete", [
    "middlewares" => [
        "RequireAdminLogin"
    ],
    function(Request $request, int $id) {
        return new Response(202, Admin\User::deleteUser($request, $id));
    }
]);