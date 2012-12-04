<?php

class Load
{

	private $loaded;
	private $template;

	public function __construct()
	{
		$template = new Template();
	}

	public function module($file)
	{

		if(file_exists(ROOT . DS . 'application' . DS . 'modules' . DS . $file . '.php'))
		{
			include ROOT . DS . 'application' . DS . 'modules' . DS . $file . '.php';

			$loaded = new $file;

			return $loaded;
		}
		else
		{
			$template->free_render(ROOT . DS . 'system' . DS . 'errors' . DS . 'loaderr.php');
		}

	}

}