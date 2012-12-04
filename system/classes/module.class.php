<?php

class Module
{

	private $template;

	public function __construct()
	{
		$this->db = new Database();
	}

}