<?php 

namespace App\Controller\Pages;

use App\Http\Request;
use App\Utils\Pagination;
use \App\Utils\View;


class Page
{
    private static function getHeader() : string
    {
        return View::render("Pages/header");
    }
    private static function getFooter() : string
    {
        return View::render("Pages/footer");
    }
    public static function getPage(string $title, string $content) : string
    {
        return View::render("Pages/page", [
            "title" => $title,
            "content" => $content,
            "header" => self::getHeader(),
            "footer" => self::getFooter()
        ]);
    }

    public static function getPagination(Request $request, Pagination $pagination) : string
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

            $links .= View::render("Pages/pagination/link", [
                "page" => $page['page'],
                "link" => $link,
                "active" => $page['current'] ? "active" : ""
            ]);
        }
        return View::render("Pages/pagination/box", [
            "links" => $links
        ]);
    }
}