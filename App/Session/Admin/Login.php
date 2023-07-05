<?php

namespace App\Session\Admin;

use App\Http\Request;
use App\Model\Entity\User as UserEntity;

class Login
{
    private static function init() : void
    {
        if(session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }

    public static function create(UserEntity $user) : bool
    {
        self::init();
        $_SESSION['admin']['user'] = [
            "id" => $user->id,
            "name" => $user->name,
            "email" => $user->email
        ];

        return true;
    }

    public static function logout() : bool
    {
        self::init();

        unset($_SESSION["admin"]["user"]);

        return true;
    }

    public static function isLogged() :bool
    {
        self::init();
        return isset($_SESSION["admin"]["user"]["id"]);
    }
}