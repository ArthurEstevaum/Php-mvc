<?php

namespace App\Controller\Admin;

use App\Http\Request;
use App\Utils\View;

class Home extends Page
{
    public static function getHome(Request $request) : string
    {
        $content = View::render('Admin/modules/home/index', []);
        return parent::getPanel("HOME - Arthur Estevão", $content, "home");
    }
}