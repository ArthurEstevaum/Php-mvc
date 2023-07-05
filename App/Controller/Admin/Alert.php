<?php

namespace App\Controller\Admin;
use App\Utils\View;

class Alert
{
    public static function getSuccess(string $message) : string
    {
        return View::render('Admin/alert/success', [
            "type" => "success",
            "message" => $message
        ]);
    }
    public static function getError(string | null $message) : string
    {
        return View::render('Admin/alert/error', [
            "type" => "danger",
            "message" => $message
        ]);
    }
}