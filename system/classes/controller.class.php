<?php

class Controller extends Load
{

	public function __construct()
	{
		$this->template = new Template();
		$this->load = new Load();
	}

}