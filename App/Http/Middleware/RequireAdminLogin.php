<?php

namespace App\Http\Middleware;

use App\Http\Request;
use App\Interfaces\MiddlewareInterface;
use App\Session\Admin\Login as SessionAdminLogin;
use Closure;

class RequireAdminLogin implements MiddlewareInterface
{
    public function handle(Request $request, Closure $next)
    {
        if(!SessionAdminLogin::isLogged()) {
            $request->router->redirect("/admin/login");
        }
        return $next($request);
    }
}