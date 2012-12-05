<?php

class Index extends Controller
{

	private $data;

	public function __construct()
	{
		parent::__construct();

	}

	public function index()
	{
		$this->data = array(array('hello' => 'first', 'hi' => 'second'));

		$this->template->render('index/index', $this->data);
	}

}