<?php
namespace Engine;
use Engine\Routing\Routing as Route;
use Engine\Request\Request;
class Kernel
{
    public function init()
    {
        include __DIR__.'/../../routes/web.php';
        $request = new Request();
        $request->init();

        $router = new Route();
        $router->execute($request);

    }
}