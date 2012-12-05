<?php


// Set up Unicent to work with the environment that the user has chosen
function environment()
{

	// If the environment is set to development
	// then we'll display all of the errors for debugging
	if(ENVIRONMENT == 'development')
	{
		error_reporting(E_ALL);
		ini_set('display_errors','On');
	}
	else
	{
		// If we are not sing a development environment, then
		// we will not show any of the errors, but we will log them
		error_reporting(E_ALL);
		ini_set('display_errors','Off');
		ini_set('log_errors', 'On');
		ini_set('error_log', ROOT .  DS . 'system' . DS . 'logs' . DS . 'error.log');
	}

}

// This is too keep everything sanatized
function strip_slashes_deep($data)
{

	if(is_array($data))
	{
		$data = array_map('strip_slashes_deep', $data);
	}
	else
	{
		$data = stripslashes($data);
	}

}

// Sanatize GET, POST and COOKIE
function remove_magic_quotes()
{

	if(get_magic_quotes_gpc())
	{
		$_GET 	= strip_slashes_deep($_GET		);
		$_POST 	= strip_slashes_deep($_POST		);
		$_COOKIE= strip_slashes_deep($_COOKIE	);
	}

}


// Unset all of our globals
function unregister_globals()
{

	if(ini_get('register_globals'))
	{
		$data = array('_SESSION', '_POST', '_GET', '_COOKIE', '_REQUEST', '_SERVER', '_ENV', '_FILES');

		foreach($data as $value)
		{
			foreach($GLOBALS[$value] as $key => $value)
			{
				if($value === $GLOBALS[$key])
				{
					unset($GLOBALS[$key]);
				}
			}
		}
	}

}

// This will be used with loading in our controllers
function hook()
{

	include ROOT . DS . 'system' . DS . 'classes' . DS . 'router.php';

	global $url;

	$controller = '';
	$function = 'index';
	$action = '';

	$url_array = array();
	$url_array = explode('/', $url);

	if(!isset($url_array[0]) || $url_array[0] == '') {
		$url_array = array('0' => 'index', '1' => 'index');
	}

	// 	We'll have a look in our routes to see if we have this rooted
	$router = new Router();

	$controller = $router->route($url_array[0]);
	array_shift($url_array);

	if(isset($url_array[0]))
	{
		$function = $url_array[0];
		array_shift($url_array);
	}

	if(isset($url_array))
	{
		$action = $url_array;
	}

	$call = $controller;
	$controller = ucfirst($controller);
	$model = rtrim($controller, 's');

	$run = new $controller($function, $call, $action);

	if((int)method_exists($controller, $function))
	{
		call_user_func_array(array($run, $function), $action);
	}
	else
	{
		// An error has occured if we come here
	}

}

function get_instance()
{
	return Controller::get_instance();
}

function __autoload($class)
{

	$class = strtolower($class);

	if(file_exists( ROOT . DS . 'system' . DS . 'classes' . DS . $class . '.class.php'))
	{

		require_once( ROOT . DS . 'system' . DS . 'classes' . DS . $class . '.class.php');

	}
	else if (file_exists( ROOT . DS . 'application' . DS . 'controllers' . DS . $class . '.php')) 
	{

		require_once( ROOT . DS . 'application' . DS . 'controllers' . DS . $class . '.php');
	
	}
	else if (file_exists( ROOT . DS . 'application' . DS . 'modules' . DS . $class . '.php'))
	{
		
		require_once( ROOT . DS . 'application' . DS . 'modules' . DS . $class . '.php');

	}

}

environment();
remove_magic_quotes();
unregister_globals();
hook();