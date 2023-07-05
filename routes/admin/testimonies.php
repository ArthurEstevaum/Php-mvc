<?php

use App\Http\Response;
use App\Controller\Admin;
use App\Http\Request;

//Rota de listagem de depoimentos
$objRouter->get('/admin/testimonies', [
    "middlewares" => [
        "RequireAdminLogin"
    ],
    function(Request $request) {
        return new Response(200, Admin\Testimony::getTestimonies($request));
    }
]);

//Rota de formulário de cadastro de depoimento
$objRouter->get("/admin/testimonies/new", [
    "middlewares" => [
        "RequireAdminLogin"
    ],
    function(Request $request) {
        return new Response(200, Admin\Testimony::getNewTestimony($request));
    }
]);

$objRouter->get("/admin/testimonies/{id}/edit", [
    "middlewares" => [
        "RequireAdminLogin"
    ],
    function (Request $request, $id) {
        return new Response(200, Admin\Testimony::getEditedTestimony($request, $id));
    }
]);

//Rota de cadastro de depoimento (post)
$objRouter->post('/admin/testimonies/new', [
    "middlewares" => [
        "RequireAdminLogin"
    ],
    function(Request $request) {
        return new Response(201 , Admin\Testimony::insertTestimony($request));
    }
]);

//Rota de edição de depoimentos(put)
$objRouter->post("/admin/testimonies/{id}/edit", [
    "middlewares" => [
        "RequireAdminLogin"
    ],
    function(Request $request, int $id) {
        return new Response(200, Admin\Testimony::updateTestimony($request, $id));
    }
]);

$objRouter->get("/admin/testimonies/{id}/delete", [
    "middlewares" => [
        "RequireAdminLogin"
    ],
    function(Request $request, int $id) {
        return new Response(202, Admin\Testimony::deleteTestimony($request, $id));
    }
]);