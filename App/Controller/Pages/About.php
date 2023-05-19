<?php

namespace App\Controller\Pages;
use App\Controller\Pages\Page;
use App\Model\Entity\Organization;
use App\Utils\View;

class About extends Page
{
    public static function getAbout() : string
    {
        $objOrganization = new Organization();
        $content = View::render("Pages/about", [
            "name" => $objOrganization->name,
            "description" => $objOrganization->description,
            "site" => $objOrganization->site
        ]);
        return parent::getPage("SOBRE - Arthur Estevão", $content);
    }
}