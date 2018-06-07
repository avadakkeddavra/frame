<?php
namespace Engine\Routing;

use Engine\Request\Request;
use App\Kernel;

class Routing
{

    public static $routes = array();
    public static $middlewareInstances = array();
    public static $middleware = array();


    public function __construct()
    {
        self::$middleware = Kernel::middleware();
    }

    public static function get($route,$handler)
    {
        self::setRoute($route,$handler,'GET');
    }

    public static function post($route,$handler)
    {
        self::setRoute($route,$handler,"POST");
    }


    protected static function setRoute($route,$handler,$method)
    {
        if($handler instanceof \Closure)
        {

            self::$routes[$route] = [
                'handler' => $handler,
                'method' => $method
            ];
        }else{
            $handler = explode('@',$handler);

            if(class_exists('App\\Controllers\\'.$handler[0]))
            {
                $controllerPath = 'App\\Controllers\\'.$handler[0];

            }elseif(class_exists('App\\Controllers\\Auth\\'.$handler[0])){

                $controllerPath ='App\\Controllers\\Auth\\'.$handler[0];
            }

            if(strpos($route,'{id}') !== false)
            {
                $route = str_replace('/{id}','',$route);
                $single = 1;
            }else{
                $single = 0;
            }

            self::$routes[$route][$method] = [
                'controller' => $controllerPath,
                'action' => $handler[1],
                'route' => $route,
                'method' => $method,
                'single' => $single
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
//        $params = explode('/',$url);

        if(strpos($url,'storage') == false) {


            $this->everyPageMiddleware($request);

            $handler = $this->findRoute($request, $url);



            if (isset($handler['handler'])) {
                call_user_func($handler['handler']);
            } else {

                //Find the controller

                if (class_exists($handler['controller'])) {

                    $controller = new $handler['controller'];
                    //Run pre-middlewares
                    $this->runMiddleware($request, $controller);

                    // Find the controller method

                    if (method_exists($controller, $handler['action'])) {
                        // Call controller method
                        if ($request->getMethod() == $handler['method']) {

                            if($handler['single'] == 1)
                            {

                                $modelName = 'App\\Model\\'.ucfirst(str_replace('/','',$handler['route'])).'s';

                                if(class_exists($modelName))
                                {

                                }elseif(class_exists(substr($modelName,0,strlen($modelName)-1))){
                                    $modelName = 'App\\Model\\'.ucfirst(str_replace('/','',$handler['route']));
                                }else{
                                    echo new \Exception('No such model founded');
                                    die();
                                }

                                $id = str_replace('/','',str_replace($handler['route'],'',$url));
                                $model = $modelName::find($id);
                                call_user_func(array($controller, $handler['action']),$model, $request);
                            }else{
                                call_user_func(array($controller, $handler['action']), $request);
                            }


                        } else {

                            echo new \Exception('Method |' . $handler['method'] . '| is not allowed here');
                            die();
                        }

                    } else {

                        echo new \Exception('Action |' . $handler['action'] . '| is not found in ' . $handler['controller']);
                        die();
                    }
                } else {
                    echo new \Exception('There are no such controller founded');
                    die();
                }
            }
        }else{
            $file = (__DIR__.'./../../..'.$url);
            header ("Content-Type: application/octet-stream");
            header ("Accept-Ranges: bytes");
            header ("Content-Length: ".filesize($file));
            header ("Content-Disposition: attachment; filename=monthlySummaries.csv");
            readfile($file);
        }
    }

    protected function runMiddleware(Request $request,$controller)
    {
        if(isset($controller->middleware))
        {
            $middleware = new $controller->middleware;

            call_user_func(array($middleware,'boot'),$request);
        }

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

    protected function findRoute(Request $request,$key)
    {

        if(strpos($key,'?') !== false)
        {
            $key = explode('?',$key)[0];

        }

        $nextKey ='/'.explode('/',$key)[1];

        $method = $request->getMethod();

        if(isset(self::$routes[$key]))
        {
            return self::$routes[$key][$method];

        }elseif(substr(self::$routes[$key],1)) {

            return substr(self::$routes[$key],1);
        }elseif (isset(self::$routes[$nextKey])){
            return self::$routes[$nextKey][$method];
        }else{
            echo new \Exception('There are no such route founded');
            die();
        }

    }

}
