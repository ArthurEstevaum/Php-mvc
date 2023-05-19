<?php

use App\Http\Response;
use App\Http\Request;
use App\Controller\Pages;

$objRouter->get("/", [
    function() {
        return new Response(200, Pages\Home::getHome());
    }
]);
$objRouter->get("/sobre", [
    function() {
        return new Response(200, Pages\About::getAbout());
    }
]);
$objRouter->get("/pagina/{idPagina}/{acao}", [
    function($idPagina, $acao) {
        return new Response(200, "Página {$idPagina} - {$acao}");
    }
]);

$objRouter->get("/depoimentos", [
    function(Request $request) {
        return new Response(200, Pages\Testimony::getTestimonies($request));
    }
]);

$objRouter->post("/depoimentos", [
    function(Request $request) {
        return new Response(200, Pages\Testimony::insertTestimony($request));
    }
]);
