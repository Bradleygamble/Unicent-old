<?php

class Test extends Module
{

	public function __construct()
	{
		parent::__construct();

		$this->db->select('hi');
	}

}