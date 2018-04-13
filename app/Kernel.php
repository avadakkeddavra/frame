<?php
namespace App;

use App\Middleware\AuthMiddleware;
use App\Middleware\RouteMiddleware;


/*
 * Uses for register middleware
 *
 * @return array()
 * */
class Kernel
{
    public static function middleware()
    {
        return [
            'web' => RouteMiddleware::class,
            'auth' => AuthMiddleware::class
        ];
    }

    public static function everyPageMiddleware()
    {
        return [
            'web' => RouteMiddleware::class
        ];
    }
}