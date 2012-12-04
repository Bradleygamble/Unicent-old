<?php

class Index extends Controller
{

	public function __construct()
	{
		parent::__construct();

		$this->template->render('index');

		$this->load->module('test');
	}

}