<?php

class Router
{

	public $routes;

	public function __construct()
	{
		include ROOT . DS . 'application' . DS . 'config' . DS . 'routes.php';

		if(isset($route) && is_array($route))
		{

			$this->routes = $route;

			if($this->routes['default_controller'] == '')
			{
				return FALSE;
			}
			else
			{
				foreach($this->routes as $key => $value)
				{
					$this->routes[$key] = strtolower($value);
				}
			}
		}
	}

	public function route($route)
	{
		if(isset($this->routes[$route]))
		{
			return $this->routes[$route];
		}
		else
		{
			return $route;
		}
	}

}
