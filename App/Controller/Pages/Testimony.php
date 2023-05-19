<?php

namespace App\Controller\Pages;

use App\Utils\View;
use App\Model\Entity\Testimony as TestimonyEntity;
use PDO;
use App\Http\Request;
use App\Utils\Pagination;
use LimitIterator;

class Testimony extends Page
{

    private static function getTestimonyItems(Request $request, &$pagination) : string
    {
        $itens = "";

        $quantidadeTotal = TestimonyEntity::countRegistries();
        $quantidadeTotal->execute();
        $quantidadeTotal = $quantidadeTotal->fetchObject()->qtd;
        $queryParams = $request->queryParams;
        $paginaAtual = $queryParams['page'] ?? 1;
        $pagination = new Pagination(results:$quantidadeTotal, currentPage:$paginaAtual, limit:3);

        $results = TestimonyEntity::getTestimonies(orderBy:"id DESC", limit:$pagination->getLimit());
        $results->execute();
        foreach($results->fetchAll(PDO::FETCH_CLASS, TestimonyEntity::class) as $testimony) {
            $itens .= View::render("/Pages/testimony/item", [
                "name" => $testimony->name,
                "mensagem" => $testimony->mensagem,
                "data" => date("d/m/Y H:i:s", strtotime($testimony->data)) 
            ]);
        }

        return $itens;
    }

    public static function getTestimonies(Request $request) : string
    {
        $viewContent = View::render("Pages/testimonies", [
            "itens" => self::getTestimonyItems($request, $pagination),
            "pagination" =>  parent::getPagination($request, $pagination)
        ]);
        return parent::getPage("DEPOIMENTOS - Arthur Estevão", $viewContent);
    }

    public static function insertTestimony($request) : string
    {
        $postVars  = $request->postVars;

        $objTestimony = new TestimonyEntity();
        $objTestimony->name = $postVars["name"];
        $objTestimony->mensagem = $postVars["mensagem"];
        $objTestimony->cadastrar();

        return self::getTestimonies($request);
    }
}