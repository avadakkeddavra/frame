<?php
require_once 'load.php';
require_once 'helpers.php';
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
$loader = new Loader();
spl_autoload_register([$loader, 'loadClass']);

$kernel = new \Engine\Kernel();

$kernel->init();
?>    
