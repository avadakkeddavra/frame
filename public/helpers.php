    <?php
    ///**
    // * Created by PhpStorm.
    // * User: smartit-9
    // * Date: 23.03.18
    // * Time: 12:12
    // */

    use Xiaoler\Blade\FileViewFinder;
    use Xiaoler\Blade\Factory;
    use Xiaoler\Blade\Compilers\BladeCompiler;
    use Xiaoler\Blade\Engines\CompilerEngine;
    use Xiaoler\Blade\Filesystem;
    use Xiaoler\Blade\Engines\EngineResolver;

    function view($tempalte,array $data = null)
    {

        $dir = __DIR__;
        $path = ["$dir/../resources/views/","$dir/../resources/layouts/","$dir/../resources/"];         // your view file path, it's an array
        $cachePath = "$dir/../resources/cached";     // compiled file path

        $file = new Filesystem;
        $compiler = new BladeCompiler($file, $cachePath);



        // you can add a custom directive if you want
        $compiler->directive('datetime', function($timestamp) {
            return preg_replace('/(\(\d+\))/', '<?php echo date("Y-m-d H:i:s", $1); ?>', $timestamp);
        });

        $compiler->directive('auth', function() {
            return Auth::guest();
        });

        $resolver = new EngineResolver;
        $resolver->register('blade', function () use ($compiler) {
            return new CompilerEngine($compiler);
        });

        $filefinder = new FileViewFinder($file, $path);

        $factory = new Factory($resolver, $filefinder);
        echo $factory->make($tempalte, $data)->render();

    }


    function asset($path)
    {
        return '/assets/'.$path;
    }

    function storage_path()
    {
        return '/var/www/frame/storage';
    }

    function redirect($path)
    {
        header('Location:/'.$path);
    }

    function download($filename)
    {
        if (file_exists($filename)) {
            // сбрасываем буфер вывода PHP, чтобы избежать переполнения памяти выделенной под скрипт
            // если этого не сделать файл будет читаться в память полностью!
            if (ob_get_level()) {
                ob_end_clean();
            }
            // заставляем браузер показать окно сохранения файла
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename=' . basename($filename));
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($filename));
            // читаем файл и отправляем его пользователю
            readfile($filename);
            exit;
        }
    }

    function session($key)
    {
        return $_SESSION[$key];
    }

    function session_set($key,$value)
    {
        $_SESSION[$key] = $value;
    }

//    function session_unset($key)
//    {
//        unset($_SESSION[$key]);
//    }