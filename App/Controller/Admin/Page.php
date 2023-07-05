<?php

namespace App\Controller\Admin;

use App\Http\Request;
use App\Utils\Pagination;
use App\Utils\View;

class Page
{

    private static array $modules = [
        "home" => [
            "label" => "Home",
            "link" => "/admin"
        ],
        "testimonies" => [
            "label" => "Depoimentos",
            "link" => "/admin/testimonies"
        ],
        "users" => [
            "label" => "Usuários",
            "link" => "/admin/users"
        ]
    ];


    private static function getMenu(string $currentModule) : string
    {
        $links = "";
        foreach (self::$modules as $hash => $module) {
            $links .= View::render('Admin/menu/link', [
                "label" => $module["label"],
                "link" => $_ENV["URL"] . $module["link"],
                "current" => $hash === $currentModule ? "text-danger" : ""
            ]);
        }
        return View::render('Admin/menu/box', [
            "links" => $links
        ]);
    }

    protected static function getPagination(Request $request, Pagination $pagination) : string
    {
        $pages = $pagination->getPages();
        if(count($pages) <= 1) {
            return '';
        }

        $links = '';

        $url = $request->getRouter()->getCurrentUrl();

        $queryParams = $request->queryParams;

        foreach($pages as $page) {

            $queryParams['page'] = $page['page'];
            $link = $url . '?' . http_build_query($queryParams);

            $links .= View::render("Admin/pagination/link", [
                "page" => $page['page'],
                "link" => $link,
                "active" => $page['current'] ? "active" : ""
            ]);
        }
        return View::render("Admin/pagination/box", [
            "links" => $links
        ]);
    }

    public static function getPage(string $title, string $content) : string
    {
        return View::render("Admin/page", [
            "title" => $title,
            "content" => $content
        ]);
    }

    public static function getPanel(string $title, string $content, string $currentModule) : string
    {
        $contentPanel = View::render('Admin/panel',[
            "menu" => self::getMenu($currentModule),
            "content" => $content
        ]);
        return self::getPage($title, $contentPanel);
    }
}