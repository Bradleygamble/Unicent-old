<?php

class Database
{

	private $select;
	private $query;
	private $result;
	private $db_handle;
	private $connection;
	private $database;
	private $template;

	public function __construct()
	{

		$template = new Template();

		if(DB_HOST == '' || DB_USER == '' || DB_DATA == '')
		{
			$template->free_render(ROOT . DS . 'system' . DS . 'errors' . DS . 'mysqlconferr.php');
			die();
		}

		$connection = mysql_connect(DB_HOST, DB_USER, DB_PASS);

		if($connection)
		{
			$database = mysql_select_db(DB_DATA);

			if(!$database)
			{
				include ROOT . DS . 'system' . DS . 'errors' . DS . 'dbconn.php';
			}

		}
		else
		{
			include ROOT . DS . 'system' . DS . 'errors' . DS . 'mysqlconn.php';
		}

	}

	public function select($string)
	{
		$select = "SELECT " . $string . " FROM ";
	}

}