<?php
namespace App\Middleware;


use Engine\Request\Request;

class RouteMiddleware
{
    public function boot(Request $request)
    {
        if($request->method != 'GET')
        {
            return false;
        }

        return true;
    }
}