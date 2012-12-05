<?php

class Load
{

	private $loaded;
	private $template;

	public function __construct()
	{
		$template = new Template();
	}

	public function module($file, $classname = FALSE)
	{
		if(file_exists(ROOT . DS . 'application' . DS . 'modules' . DS . $file . '.php'))
		{
			include ROOT . DS . 'application' . DS . 'modules' . DS . $file . '.php';

			$UN =& get_instance();

			if($classname)
			{
				$UN->$classname = new $file();
			}
			else
			{
				$UN->$file = new $file();
			}
		}
		else
		{
			$template->free_render(ROOT . DS . 'system' . DS . 'errors' . DS . 'loaderr.php');
		}
	}

}