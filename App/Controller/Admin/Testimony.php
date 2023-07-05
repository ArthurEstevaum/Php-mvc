<?php

namespace App\Controller\Admin;

use App\Http\Request;
use App\Utils\View;
use App\Utils\Pagination;
use PDO;
use App\Model\Entity\Testimony as TestimonyEntity;

class Testimony extends Page
{
    public static function getTestimoniesItems(Request $request, &$pagination) : string
    {
        $itens = "";

        $quantidadeTotal = TestimonyEntity::countRegistries();
        $quantidadeTotal->execute();
        $quantidadeTotal = $quantidadeTotal->fetchObject()->qtd;
        $queryParams = $request->queryParams;
        $paginaAtual = $queryParams['page'] ?? 1;
        $pagination = new Pagination(results:$quantidadeTotal, currentPage:$paginaAtual, limit:5);

        $results = TestimonyEntity::getTestimonies(orderBy:"id DESC", limit:$pagination->getLimit());
        $results->execute();
        foreach($results->fetchAll(PDO::FETCH_CLASS, TestimonyEntity::class) as $testimony) {
            $itens .= View::render("/Admin/modules/testimonies/item", [
                "id" => $testimony->id,
                "name" => $testimony->name,
                "mensagem" => $testimony->mensagem,
                "data" => date("d/m/Y H:i:s", strtotime($testimony->data)) 
            ]);
        }

        return $itens;
    }

    public static function getTestimonies(Request $request) : string
    {
        $content = View::render('Admin/modules/testimonies/index',[
            "items" => self::getTestimoniesItems($request, $pagination),
            "status" => self::getStatus($request),
            "pagination" => parent::getPagination($request, $pagination)
        ]);

        return parent::getPanel("Depoimentos - ADM", $content, "testimonies");
    }

    public static function getNewTestimony(Request $request) : string
    {
        $content = View::render('Admin/modules/testimonies/form',[
            "title" => "Cadastrar depoimento",
            "status" => self::getStatus($request),
            "name" => "",
            "mensagem" => "",
        ]);

        return parent::getPanel("Cadastrar depoimento - ADM", $content, "testimonies");
    }

    public static function getEditedTestimony(Request $request, int $id) : string
    {
        $objTestimony = TestimonyEntity::getTestimonyById($id);

        if(!$objTestimony instanceof TestimonyEntity) {
            $request->getRouter()->redirect("/admin/testimonies");
        }

        $content = View::render('Admin/modules/testimonies/form', [
            "title" => "Editar depoimento",
            "name" => $objTestimony->name,
            "mensagem" => $objTestimony->mensagem,
            "status" => self::getStatus($request)
        ]);
        
        return parent::getPanel("Editar depoimento - ADM", $content, "testimonies");
    }

    public static function insertTestimony(Request $request) : string
    {
        $postVars  = $request->postVars;

        $objTestimony = new TestimonyEntity();
        $objTestimony->name = $postVars["name"] ?? "";
        $objTestimony->mensagem = $postVars["mensagem"] ?? "";
        $objTestimony->cadastrar();

        $request->router->redirect("/admin/testimonies/{$objTestimony->id}/edit?status=created");
        return "";
    }

    public static function updateTestimony(Request $request, int $id) : string
    {
        $postVars = $request->postVars;
        
        $objTestimony = new TestimonyEntity();
        $objTestimony->name = $postVars["name"] ?? "";
        $objTestimony->mensagem = $postVars["mensagem"] ?? "";
        $objTestimony->update($id);

        $request->router->redirect("/admin/testimonies/{$id}/edit?status=updated");
        
        return "";
    }

    public static function deleteTestimony(Request $request, int $id) : string
    {
        TestimonyEntity::deleteTestimonyById($id);

        $request->router->redirect("/admin/testimonies?status=deleted");

        return "";
    }

    private static function getStatus(Request $request) : string
    {
        $queryParams = $request->queryParams;

        if(!isset($queryParams["status"])) return "";

        switch($queryParams["status"]) {
            case "created":
                return Alert::getSuccess("Depoimento criado com sucesso");
                break;
            case "updated":
                return Alert::getSuccess("Depoimento editado com sucesso");
                break;
            case "deleted" :
                return Alert::getSuccess("Depoimento deletado com sucesso");
                break;
            default :
                return "";
        }
    }
}