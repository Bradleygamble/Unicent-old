<?php

class Controller extends Load
{
	private static $instance;

	public function __construct()
	{
		self::$instance =& $this;

		$this->template = new Template();
		$this->load = new Load();
	}

	public function get_instance()
	{
		return self::$instance;
	}

}