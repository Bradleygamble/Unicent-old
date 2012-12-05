<?php

class Database
{

	// Set up some privates for building our query
	private $get;
	private $select;
	private $where;
	private $order;
	private $limit;

	// These will process our query
	private $query;
	private $result;
	private $db_handle;
	private $connection;
	private $database;
	private $template;

	/*
	 *		When we construct the Database class, we need to do a few things
	 *
	 *		We first set up a connection to the MySQL Server and the Database
	 *		if a connection can not be established we will display an error
	 */
	public function __construct()
	{	
		// 		Prepare the template engine 
		$template = new Template();

		// 		Check to see if we have the information to continue
		if(DB_HOST == '' || DB_USER == '' || DB_DATA == '')
		{
			// 		If we don't have any of the information, we'll show an error and die
			$template->free_render(ROOT . DS . 'system' . DS . 'errors' . DS . 'mysqlconferr.php');
			die();
		}

		// 		We have some information so we'll attempt to connect to the MySQL Server
		$this->connection = mysql_connect(DB_HOST, DB_USER, DB_PASS);

		// 		Check to see if the connection was successful
		if($this->connection)
		{	
			//		The connection was successful, we so'll now attempt to connect to the database
			$this->database = mysql_select_db(DB_DATA);

			//		Check to see if the database connection was successful
			if(!$this->database)
			{	
				// 		The connection failed, so we display an error
				include ROOT . DS . 'system' . DS . 'errors' . DS . 'dbconn.php';
			}
		}
		else
		{	
			// 		The connection failed, so we display an error
			include ROOT . DS . 'system' . DS . 'errors' . DS . 'mysqlconn.php';
		}
	}

	//		$this->db->get() | Used to select data from a table
	public function get($array = array(), $table)
	{	

		// 		Check to see if we already have a query stored
		if($this->get != NULL)
		{	
			// 		We had a query stored, so we'll empty it
			$this->select = NULL;
		}

		//		Check to see if our data is an array or not
		if(!is_array($array))
		{	
			// 		Our data was not an array, so we'll make it one
			$array = array($array);
		}

		// 		Now we'll loop over the data we were given
		foreach($array as $key => $value)
		{
			//		If we already have a query stored
			if($this->select != '')
			{	
				//		Before we can append the data, we need to seperate it with a comma
				$this->select .= ", ";
			}

			// 		If the value is * then we'll select all of the rows
			if($value == '*')
			{	
				// 		Set the select to be all
				$this->select = $this->_sanitize($value);
			}
			else
			{	
				//		We don't have any select data, so we'll start fresh
				$this->select = "`" . $this->_sanitize($value) . "`";
			}
		}
		
		// 		This builds our whole query, ready to be run
		$this->get = "SELECT " . $this->select . " FROM " . $table . $this->where . $this->order . $this->limit;

		// 		Run the query
		return $this->_run($this->get);
	}

	//		$this->db->get() | Used for where statements 
	public function where($array = array())
	{	
		//		If we already have a query stored
		if($this->get != '')
		{
			//		Empty the stored query
			$this->where = NULL;
		}

		if(!is_array($array))
		{	
			$array = array($array);
		}

		foreach($array as $key => $value)
		{
			if($this->where == '')
			{
				$this->where = " WHERE `" . $this->_sanitize($key) . "`='" . $this->_sanitize($value) . "' ";
			}
			else
			{
				$this->where .= "AND `" . $this->_sanitize($key) . "`='" . $this->_sanitize($value) . "' ";
			}
		}
	}

	public function and_where($array = array())
	{
		$loops = count($array);
		$loop = 0;

		if(!is_array($array))
		{
			$array = array($array);
		}

		$this->where .= "AND (";
			foreach($array as $key => $value)
			{
				$loop++;

				$this->where .= "`" . $this->_sanitize($key) . "`='" . $this->_sanitize($value) . "'";

				if($loops != 1 && $loops != $loop)
				{
					$this->where .= " AND ";
				}
			}
		$this->where .= ") ";
	}

	public function or_where($array = array())
	{
		$loops = count($array);
		$loop = 0;

		if(is_array($array))
		{
			$array = array($array);
		}

		$this->where .= "OR (";
			foreach($array as $key => $value)
			{
				$loop++;

				$this->where .= "`" . $this->_sanitize($key) . "`='" . $this->_sanitize($value) . "'";

				if($loops != 1 && $loops != $loop)
				{
					$this->where .= " AND ";
				}
			}
		$this->where .= ") ";
	}

	public function limit($start, $end = 0)
	{
		if($this->get != '')
		{
			$this->limit = NULL;
		}

		$this->limit = "LIMIT " . $this->_sanitize($start) . ", " . $this->_sanitize($end);
	}

	public function order_by($column, $direction)
	{
		if($this->get != '')
		{
			$this->order = NULL;
		}

		if($this->order == NULL)
		{
			$this->order = "ORDER BY " . $this->_sanitize($column) . ' ' . $this->_sanitize($direction);
		}
		else
		{
			$this->order = ", " . $this->_sanitize($column) . ' ' . $this->_sanitize($direction);
		}
	}

	public function result_row()
	{
		return mysql_fetch_assoc($this->result);
	}

	public function result_array()
	{
		return mysql_fetch_array($this->result);
	}

	public function last_query()
	{
		return $this->get;
	}

	public function free_query()
	{
		$this->where = NULL;
		$this->limit = NULL;
		$this->order = NULL;
		$this->select = NULL;
	}

	public function free_result()
	{
		$this->result = NULL;
	}

	private function _run($query)
	{
		$this->result = mysql_query($query);
	}

	private function _sanitize($string)
	{
		$string = stripslashes($string);
		$string = mysql_real_escape_string($string);

		return $string;
	}

}