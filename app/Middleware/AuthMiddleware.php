<?php
/**
 * Created by PhpStorm.
 * User: smartit-9
 * Date: 13.04.18
 * Time: 11:28
 */

namespace App\Middleware;


class AuthMiddleware
{
    public function boot()
    {

        if(\Auth::guest())
        {
            redirect('login');
        }

        return true;
    }
}