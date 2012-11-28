<?php

class Controller
{

	protected $_model;
	protected $_controller;
	protected $_action;
	protected $_template;
	protected $_load;
	protected $_dev;

	function __construct($model, $controller, $action)
	{

		$this->_controller = $controller;
		$this->_action = $action;
		$this->_model = $model;
		$this->_dev = new Dev();

		$this->$model = new $model;
		$this->_template = new Template($controller, $action);
		$this->db = new SQLQuery();

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

			if(DEV_LOGS == 'On') { $this->_dev->devlog('Loaded user module [' . $load_file . ']'); }
			require_once( ROOT . DS . 'application' . DS . 'modules' . DS . $load_file . '.php');

		}
		else
		{

			if(DEV_LOGS == 'On') { $this->_dev->devlog('Loaded system module [' . $load_file . ']'); }
			require_once( ROOT . DS . 'system' . DS . 'modules' . DS . $load_file . '.php');

		}

		$class = ucfirst($set_class);

		$this->_load = new $class();

		return $this->_load;

	}

}