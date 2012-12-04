<?php

class Index extends Controller
{

	public function __construct()
	{
		parent::__construct();

		$this->template->render('index');

		$database = new Database();

		$database->where(array('col1' => '2', 'col2' => '4'));
		$database->or_where(array('col1' => '2', 'col2' => '4'));
		$database->and_where(array('col1' => '2', 'col2' => '4'));
		$database->or_where(array('col1' => '2', 'col2' => '4'));
		$database->get(array('*'), 'table');
	}

}