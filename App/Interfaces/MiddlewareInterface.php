<?php

namespace App\Interfaces;

use App\Http\Request;
use Closure;

interface MiddlewareInterface
{
    public function handle(Request $request, Closure $next);
}