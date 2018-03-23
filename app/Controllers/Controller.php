<?php
namespace App\Controllers;

use App\Kernel;
class Controller
{
	public $middleware;

    public function middleware($key)
    {
		$middlewares = Kernel::middleware();

		if($middlewares[$key])
		{
			$this->middleware = $middlewares[$key];
		}else{
			echo new \Exception('Invalid middleware');
		}
    }
}

?>

