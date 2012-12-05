<?php

class Index extends Controller
{

	private $data;

	public function __construct()
	{
		parent::__construct();

		$hi = array('hi' => array('0' => 'hello', '1' => 'test'));

		$this->data = $hi;

		$this->template->render('index/index', $this->data);
	}

}