<?php
/**
 * Created by PhpStorm.
 * User: smartit-9
 * Date: 23.03.18
 * Time: 12:12
 */
function view($template,array $params)
{
    if(strpos($template,'::') !== false)
    {
        $folder = explode('::',$template)[0];
        $file = explode('::',$template)[1].'.php';
        $path =  __DIR__."/./../resources/views/$folder/$file";
    }else{
        $path =  __DIR__."/./../resources/views/$template.php";
    }


    foreach($params as $key => $param)
    {
        ${$key} = $param;
    }

    require_once $path;
}

function get_header($title = null)
{
    if($title == null)
    {
        include __DIR__."/./../resources/views/layouts/header.php";
    }else{
        include __DIR__."/./../resources/views/layouts/$title.php";
    }
}

function get_footer($title = null)
{
    if($title == null)
    {
        include __DIR__."/./../resources/views/layouts/footer.php";
    }else{
        include __DIR__."/./../resources/views/layouts/$title.php";
    }
}

function asset($path)
{
    return '/assets/'.$path;
}

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
