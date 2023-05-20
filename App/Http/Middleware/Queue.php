<?php

namespace App\Http\Middleware;

use App\Http\Request;
use App\Http\Response;
use Closure;
use Exception;

class Queue
{
    private static array $map = [];
    private static array $default = [];
    private array $middlewares = [];
    private Closure $controller;
    private array $controllerArgs = [];

    public function __construct(array $middlewares, Closure $controller, array $controllerArgs)
    {
        $this->middlewares = array_merge(self::$default, $middlewares);
        $this->controller = $controller;
        $this->controllerArgs = $controllerArgs;
    }

    public function next(Request $request) : Response
    {
        if(empty($this->middlewares)) {
            return call_user_func_array($this->controller, $this->controllerArgs);
        }

        $middleware = array_shift($this->middlewares);

        if(!isset(self::$map[$middleware])) {
            throw new Exception("Error processing request", 500);
        }

        $queue = $this;
        $next = function(Request $request) use ($queue) {
            return $queue->next($request);
        };

        return (new self::$map[$middleware])->handle($request, $next);
    }

    public static function setMap(array $map) : void
    {
        self::$map = $map;
    }

    public static function setDefault(array $default) : void
    {
        self::$default = $default;
    }
}