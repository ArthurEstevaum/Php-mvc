<?php

namespace App\Controller\Admin;

use App\Controller\Admin\Page;
use App\Utils\View;
use App\Http\Request;
use App\Model\Entity\User as UserEntity;
use App\Session\Admin\Login as SessionAdminLogin;
use Exception;

class Login extends Page
{
    public static function getLogin(Request $request, string $errorMessage = null) : string
    {
        $content = View::render("Admin/login",[
            "status" => isset($errorMessage) ? Alert::getError($errorMessage)
            : ""
        ]);

        return parent::getPage("LOGIN - Arthur Estevão", $content);
    }

    public static function setLogout(Request $request) : string
    {
        SessionAdminLogin::logout();

        $request->router->redirect("/admin/login");

        return '';
    }

    public static function authenticateLogin(Request $request) : string
    {
        $email = $request->postVars["email"] ?? "";
        $password = $request->postVars["password"] ?? "";
        $user = UserEntity::getUserByEmail($email);

        if(!$user instanceof UserEntity) {
            throw new Exception(self::getLogin($request, "Email ou senha inválidos"), 401);
        }

        if(!password_verify($password, $user->password)) {
            throw new Exception(self::getLogin($request, "Email ou senha inválidos"), 401); 
        }

        SessionAdminLogin::create($user);
        $request->router->redirect("/admin");
        return '';
    }
}