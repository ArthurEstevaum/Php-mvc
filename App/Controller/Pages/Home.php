<?php 

namespace App\Controller\Pages;

use App\Model\Entity\Organization;
use \App\Utils\View;


class Home extends Page
{
    public static function getHome() : string
    {
        $objOrganization = new Organization();
        $content = View::render("Pages/home", [
            "name" => $objOrganization->name,
        ]);
        return parent::getPage("Home", $content);
    }
}