<?php
namespace Engine\Routing;

use Engine\Request\Request;
use App\Kernel;

class Routing
{

    public static $routes = array();

    public static function get($route,$handler)
    {
        self::setRoute($route,$handler,'GET');
    }

    public static function  post($route,$handler)
    {
        self::setRoute($route,$handler,"POST");
    }

    protected static function setRoute($route,$handler,$method)
    {
        if(!$handler instanceof \Closure)
        {
            $handler = explode('@',$handler);

            self::$routes[$route] = [
                'controller' => 'App\\Controllers\\'.$handler[0],
                'action' => $handler[1],
                'method' => $method
            ];
        }else{
            self::$routes[$route] = [
                'handler' => $handler,
                'method' => $method
            ];
        }
    }

    public static function getRoutes()
    {
        print_r(self::$routes);
    }


    public function execute(Request $request)
    {
        $url = $_SERVER['REQUEST_URI'];

        $this->everyPageMiddleware($request);

        $handler = $this->findRoute($url);
        if(isset($handler['handler']))
        {
            call_user_func($handler['handler']);
        }else{
            if(class_exists($handler['controller']))
            {
                $controller = new $handler['controller'];

                $this->runMiddleware($request,$controller);
                if(method_exists($controller,$handler['action']))
                {
                    if($request->method() == $handler['method'])
                    {
                        call_user_func(array($controller, $handler['action']),$request);
                    }else{
                        echo new \Exception('Method |'.$handler['method'].'| is not allowed here');
                        die();
                    }

                }else{

                    echo new \Exception('Action |'.$handler['action'].'| is not found in '. $handler['controller']);
                    die();
                }
            }else{
                echo new \Exception('There are no such controller founded');
                die();
            }
        }

    }

    protected function runMiddleware(Request $request,$controller)
    {
        $middleware = new $controller->middleware;
        call_user_func(array($middleware,'boot'),$request);
    }

    protected function everyPageMiddleware(Request $request)
    {
        $middlewares = Kernel::everyPageMiddleware();

        foreach($middlewares as $middleware)
        {
            $response = call_user_func(array(new $middleware,'boot'),$request);
            if(!$response)
            {
                echo new \Exception('Whoooops.)');
                die();
            }
        }
    }

    protected function findRoute($key)
    {
        $key = explode('?',$key)[0];
        if(isset(self::$routes[$key]))
        {
            return self::$routes[$key];

        }elseif(substr(self::$routes[$key],1)) {

            return substr(self::$routes[$key],1);
        }else{
            echo new \Exception('There are no such route founded');
            die();
        }

    }

}
?>
