<?php

namespace App\Controller\Admin;
use App\Http\Request;
use App\Utils\View;
use App\Utils\Pagination;
use App\Model\Entity\User as UserEntity;
use Exception;
use PDO;

class User extends Page
{
    public static function getUsersItems(Request $request, &$pagination) : string
    {
        $itens = "";

        $quantidadeTotal = UserEntity::countRegistries();
        $quantidadeTotal->execute();
        $quantidadeTotal = $quantidadeTotal->fetchObject()->qtd;
        $queryParams = $request->queryParams;
        $paginaAtual = $queryParams['page'] ?? 1;
        $pagination = new Pagination(results:$quantidadeTotal, currentPage:$paginaAtual, limit:5);

        $results = UserEntity::getUsers(orderBy:"id DESC", limit:$pagination->getLimit());
        $results->execute();
        foreach($results->fetchAll(PDO::FETCH_CLASS, UserEntity::class) as $user) {
            $itens .= View::render("/Admin/modules/users/item", [
                "id" => $user->id,
                "name" => $user->name,
                "email" => $user->email
            ]);
        }

        return $itens;
    }

    public static function getUsers(Request $request) : string
    {
        $content = View::render('Admin/modules/users/index',[
            "items" => self::getUsersItems($request, $pagination),
            "status" => self::getStatus($request),
            "pagination" => parent::getPagination($request, $pagination)
        ]);

        return parent::getPanel("Usuários - ADM", $content, "users");
    }

    public static function getNewUser(Request $request) : string
    {
        $content = View::render('Admin/modules/users/form',[
            "title" => "Cadastrar usuário",
            "status" => self::getStatus($request),
            "name" => "",
            "email" => "",
            "password" => ""
        ]);

        return parent::getPanel("Cadastrar Usuário - ADM", $content, "users");
    }

    public static function getEditedUser(Request $request, int $id) : string
    {
        $user = UserEntity::getUserById($id);

        if(!$user instanceof UserEntity) {
            $request->getRouter()->redirect("/admin/users");
        }

        $content = View::render('Admin/modules/users/form', [
            "title" => "Editar depoimento",
            "name" => $user->name,
            "email" => $user->email,
            "status" => self::getStatus($request)
        ]);
        
        return parent::getPanel("Editar usuário - ADM", $content, "users");
    }

    public static function insertUser(Request $request) : string
    {
        $postVars  = $request->postVars;

        $user = new UserEntity();
        $user->name = $postVars["name"] ?? "";
        $user->email = $postVars["email"] ?? "";
        $user->password = password_hash($postVars["password"], null) ?? "";
        if(UserEntity::getUserByEmail($user->email) instanceof UserEntity) {
            $request->router->redirect("/admin/users/new?status=emailAlreadyExists");
        }
        $user->cadastrar();

        $request->router->redirect("/admin/users/{$user->id}/edit?status=created");
        return "";
    }

    public static function updateUser(Request $request, int $id) : string
    {
        $postVars = $request->postVars;
        
        $user = new UserEntity();
        $user->name = $postVars["name"] ?? "";
        $user->email = $postVars["email"] ?? "";
        $user->password = password_hash($postVars["password"], null) ?? "";
        $userEntity = userEntity::getUserByEmail($user->email);
        if($userEntity instanceof UserEntity && $userEntity->id != $id) {
            $request->router->redirect("/admin/users/{$id}/edit?status=emailAlreadyExists");
        }
        $user->update($id);

        $request->router->redirect("/admin/users/{$id}/edit?status=updated");
        
        return "";
    }

    public static function deleteUser(Request $request, int $id) : string
    {
        UserEntity::deleteUserById($id);

        $request->router->redirect("/admin/users?status=deleted");

        return "";
    }

    private static function getStatus(Request $request) : string
    {
        $queryParams = $request->queryParams;

        if(!isset($queryParams["status"])) return "";

        switch($queryParams["status"]) {
            case "created":
                return Alert::getSuccess("Usuário criado com sucesso");
                break;
            case "updated":
                return Alert::getSuccess("Usuário editado com sucesso");
                break;
            case "deleted" :
                return Alert::getSuccess("Usuário deletado com sucesso");
                break;
            case "emailAlreadyExists" :
                return Alert::getError("Este email já é utilizado por um usuário");
            default :
                return "";
        }
    }
}