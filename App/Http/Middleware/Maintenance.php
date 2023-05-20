<?php

namespace App\Http\Middleware;
use App\Http\Request;
use App\Http\Response;
use App\Interfaces\MiddlewareInterface;
use Closure;
use Exception;

class Maintenance implements MiddlewareInterface
{
    public function handle(Request $request, Closure $next) : Response
    {
        if($_ENV['MAINTENANCE'] == "true") {
            throw new Exception("A aplicação está em manutenção, tente novamente mais tarde", 200);
        }

        return $next($request);
    }
}