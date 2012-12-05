<?php

class Controller extends Load
{
	private static $instance;

	public function __construct()
	{
		self::$instance =& $this;

		$this->template = new Template();
		$this->load = new Load();
		$this->route = new Router();
	}

	public function get_instance()
	{
		return self::$instance;
	}

}