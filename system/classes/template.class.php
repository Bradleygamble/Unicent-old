<?php

class Template
{

	protected $variables = array();
	protected $_controller;
	protected $_action;

	function __construct($controller, $action)
	{

		$this->_controller = $controller;
		$this->_action = $action;

	}

	function render($data)
	{

		foreach($data as $data_key => $data_value) 
		{
			$this->variables[$data_key] = $data_value;
		}

		$output = '';

		//extract($this->variables);

		if(file_exists( ROOT . DS . 'www' . DS  . $this->_controller . DS . 'header.php'))
		{

			$output .= file_get_contents( ROOT . DS . 'www' . DS  . $this->_controller . DS . 'header.php');

		}
		else 
		{

			//file_get_contents ( ROOT . DS . 'www' . DS  . 'header.php');

		}

		$output .= file_get_contents ( ROOT . DS . 'www' . DS . $this->_controller . DS . $this->_action . '.php');

		if(file_exists(ROOT . DS . 'www' . DS . $this->_controller . DS . 'footer.php'))
		{

			$output .= file_get_contents ( ROOT . DS . 'www' . DS . $this->_controller . DS . 'footer.php');

		}
		else
		{

			//file_get_contents ( ROOT . DS . 'www' . DS . 'footer.php');

		}

		foreach($this->variables as $find_key => $find_value)
		{

			$find[] = '{[ ' . $find_key . ' ]}';
			$replace[] = $find_value;

		}

		$output = str_replace($find, $replace, $output);

		echo $output;

	}

}