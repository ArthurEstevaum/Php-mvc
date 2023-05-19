<?php

namespace App\Utils;

class View
{
    private static array $vars;
    private static function getViewContent(string $view) : string
    {
        $file = __DIR__ . "/../../Resources/View/" . $view . ".html";
        return file_exists($file) ? file_get_contents($file) : "";
    }
    public static function render(string $view, array $vars = []) : string
    {
        $viewContent = self::getViewContent($view);
        $vars = array_merge(self::$vars, $vars);
        $keys = array_keys($vars);
        $keys = array_map(function($key){
            return '{{'. $key .'}}';
        }, $keys);
        $viewContent = str_replace($keys, $vars, $viewContent);
        return $viewContent;
    }

    public static function init(array $vars = [])
    {
        self::$vars = $vars;
    }
}