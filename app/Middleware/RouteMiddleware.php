<?php
namespace App\Middleware;


use Engine\Request\Request;

class RouteMiddleware
{
    public function boot(Request $request)
    {
        if($request->getMethod() != 'GET')
        {
            return true;
        }

        return true;
    }
}