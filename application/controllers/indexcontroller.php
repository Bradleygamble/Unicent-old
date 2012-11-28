<?php

class IndexController extends Controller 
{

	public $data;
	
	public function index()
	{

		$this->data = array('title' => 'Hello world!');

		$test = $this->load_module('testmodule', 'test');

		$test->test();

		$this->render($this->data);

	}

}