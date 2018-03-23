<?php
class Loader
{

	public function loadClass($class_name)
	{
		$arr = explode('\\', $class_name);
		
		//print_r($arr);
		$path = '';
		foreach($arr as $key => $path_part)
		{
			if($key == 0)
			{
				$path_part = lcfirst($path_part);
				//echo $path_part.'<br>';
			}
			$path .= '/'.$path_part;
		}

		$file =  __DIR__.'/..'.$path.'.php';

		if(is_file($file))
		{
				//echo $file;
				require_once $file;

				return ;
		}else{

			if(strpos($class_name,'vendor') === false)
			{
                $this->loadClass(str_replace('/','\\','vendor\\'.$class_name));
			}else{
				return;
			}

		}

		
	}
}

 ?>
