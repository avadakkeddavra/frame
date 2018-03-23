<?php
/**
 * Created by PhpStorm.
 * User: smartit-9
 * Date: 23.03.18
 * Time: 10:37
 */

namespace Engine\Request;


class Request
{
    public $params = [];
    public $method;

    public function init()
    {
         $this->method = $_SERVER['REQUEST_METHOD'];
         $this->params = $_REQUEST;
    }

    public function getAll()
    {
        return $this->params;
    }

    public function get($key)
    {
        if($this->params[$key])
        {
            return $this->params[$key];
        }else{
            echo new \Exception('Undefined param '.$key.' of Request');
            die();
        }
    }

    public function method()
    {
        return $this->method;
    }
}