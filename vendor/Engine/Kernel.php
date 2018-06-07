<?php

namespace Engine;
use Engine\Routing\Routing as Route;
use Engine\Request\Request;
//use Illuminate\Database\Capsule\Manager as Capsule;
require_once __DIR__.'/../../vendor/autoload.php';
class Kernel
{
    public function init()
    {
        session_start();
        include __DIR__.'/Auth/Auth.php';
        include_once  __DIR__.'./../../database/database.php';
        include_once __DIR__.'./../../public/helpers.php';
        include __DIR__.'/../../routes/web.php';

        $request = new Request();
        $request->init();

        $router = new Route();
        $router->execute($request);
    }

}