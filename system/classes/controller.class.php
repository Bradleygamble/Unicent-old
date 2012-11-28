<?php

class Controller
{

	protected $_model;
	protected $_controller;
	protected $_action;
	protected $_template;
	protected $_load;

	function __construct($model, $controller, $action)
	{

		$this->_controller = $controller;
		$this->_action = $action;
		$this->_model = $model;

		$this->$model = new $model;
		$this->_template = new Template($controller, $action);

	}

	function render($data = array())
	{

		$this->_template->render($data);

	}

	function load_module($load_file, $set_class = '')
	{

		if($set_class == '')
		{

			$set_class = $load_file;

		}

		if(file_exists( ROOT . DS . 'application' . DS . 'modules' . DS . $load_file . '.php'))
		{

			echo 'users module didn\'t exist';
			require_once( ROOT . DS . 'application' . DS . 'modules' . DS . $load_file . '.php');

		}
		else
		{

			echo 'the module is a system module';
			require_once( ROOT . DS . 'system' . DS . 'modules' . DS . $load_file . '.php');

		}

		$class = ucfirst($set_class);

		$this->_load = new $class();

		return $this->_load;

	}

}