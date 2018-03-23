<?php
namespace App;


use App\Middleware\RouteMiddleware;

class Kernel
{
    public static function middleware()
    {
        return [
          'web' => RouteMiddleware::class,
        ];
    }

    public static function everyPageMiddleware()
    {
        return [
            'web' => RouteMiddleware::class,
        ];
    }
}