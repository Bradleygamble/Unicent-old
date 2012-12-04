<?php

class Template
{

	public function __construct()
	{
		
	}

	public function render($view, $data = array(), $return = FALSE)
	{
		if(file_exists(ROOT . DS . 'application' . DS . 'views' . DS . $view))
		{
			if($return == FALSE)
			{
				include ROOT . DS . 'application' . DS . 'views' . DS . $view;
			}
			else
			{
				return file_get_contents($view);
			}
		}
	}

	public function free_render($view, $return = FALSE)
	{
		if(file_exists($view))
		{
			if($return == FALSE)
			{
				include $view;
			}
			else
			{
				return file_get_contents($view);
			}
		}
	}

}