<?php

/*
 * Simple class loader
 * include a file by his namespace
 *
 * if file was not founded, watches into vendor;
 *
 * @returns void
 * */
class Loader
{

	public function loadClass($class_name)
	{
		$arr = explode('\\', $class_name);

		$path = '';
		foreach($arr as $key => $path_part)
		{
			if($key == 0)
			{
				$path_part = lcfirst($path_part);
			}
			$path .= '/'.$path_part;
		}

		$file =  __DIR__.'/..'.$path.'.php';

		if(is_file($file))
		{
				require_once $file;
				return ;
		}else{

			if(strpos($class_name,'vendor') === false)
			{
                $this->loadClass(str_replace('/','\\','vendor\\'.$class_name));
			}else{
                require_once __DIR__.'./../vendor/autoload.php';
			}

		}

		
	}
}

 ?>
