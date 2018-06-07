<?php
//ini_set('error_reporting', E_ALL);
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);

require_once 'load.php';

function env($key)
{
    $env = json_decode(file_get_contents(__DIR__.'/../config.json'));
    if($env->$key)
    {
        return $env->$key;
    }else{
        echo new Exception("This configuration does not exists");
        die();
    }

}

$loader = new Loader();
spl_autoload_register([$loader, 'loadClass']);

$kernel = new \Engine\Kernel();

$kernel->init();

?>    
