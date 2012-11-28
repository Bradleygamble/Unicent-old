<?php

function set_reporting() 
{

	if(DEVELOPMENT_ENVIRONMENT == TRUE)
	{

		error_reporting(E_ALL);
		ini_set('display_errors', 'On');

	} 
	else 
	{

		error_reporting(E_ALL);
		ini_set('display_errors', 'Off');
		ini_set('log_errors', 'On');
		ini_set('error_log', ROOT .  DS . 'system' . DS . 'logs' . DS . 'error.log');

	}

}

function strip_slashes_deep($value) 
{

	$value = is_array($value) ? array_map('strip_slashes_deep', $value) : stripslashes($value);

	return $value;

}

function remove_magic_quotes() 
{

	if(get_magic_quotes_gpc())
	{

		$_GET	 = strip_slashes_deep($_GET		);
		$_POST	 = strip_slashes_deep($_POST	);
		$_COOKIE = strip_slashes_deep($_COOKIE  );

	}

}

function unregister_globals() 
{

	if(ini_get('register_globals'))
	{

		$array = array('_SESSION', '_POST', '_GET', '_COOKIE', '_REQUEST', '_SERVER', '_ENV', '_FILES');

		foreach($array as $value) 
		{

			foreach($GLOBALS[$value] as $key => $var)
			{

				if($var === $GLOBALS[$key])
				{

					unset($GLOBALS[$key]);

				}

			} 

		}

	}

}

function call_hook()
{

	global $url;

	$url_array = array();
	$url_array = explode("/", $url);

	if(!isset($url_array[0]) || $url_array[0] == '') {
		$url_array = array('0' => 'index', '1' => 'index');
	}

	$controller = $url_array[0];
	array_shift($url_array);
	$action = $url_array[0];
	array_shift($url_array);
	$query = $url_array;

	$controller_name = $controller;
	$controller = ucwords($controller);
	$model = rtrim($controller, 's');
	$controller .= 'Controller';
	$dispatch = new $controller($model, $controller_name, $action);

	if((int)method_exists($controller, $action))
	{

		call_user_func_array(array($dispatch, $action), $query);

	}
	else
	{

		// Error generation
		echo 'Woops2';

	}

}

function __autoload($classname)
{

	$classname = strtolower($classname);

	if(file_exists( ROOT . DS . 'system' . DS . 'classes' . DS . $classname . '.class.php'))
	{

		require_once( ROOT . DS . 'system' . DS . 'classes' . DS . $classname . '.class.php');

	}
	else if (file_exists( ROOT . DS . 'application' . DS . 'controllers' . DS . $classname . '.php')) 
	{

		require_once( ROOT . DS . 'application' . DS . 'controllers' . DS . $classname . '.php');
	
	}
	else if (file_exists( ROOT . DS . 'application' . DS . 'models' . DS . $classname . '.php'))
	{
		
		require_once( ROOT . DS . 'application' . DS . 'models' . DS . $classname . '.php');

	}
	else
	{

		// Error generation
		require_once( ROOT . DS . 'system' . DS . 'errors' . DS . 'invalidclass.php');

	}

}

set_reporting();
remove_magic_quotes();
unregister_globals();
call_hook();