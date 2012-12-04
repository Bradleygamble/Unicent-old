<?php

class Database
{

	private $get;
	private $select;
	private $where;
	private $order;
	private $limit;

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
			//die();
		}

		$this->connection = mysql_connect(DB_HOST, DB_USER, DB_PASS);

		if($this->connection)
		{
			$this->database = mysql_select_db(DB_DATA);

			if(!$this->database)
			{
				include ROOT . DS . 'system' . DS . 'errors' . DS . 'dbconn.php';
			}

		}
		else
		{
			include ROOT . DS . 'system' . DS . 'errors' . DS . 'mysqlconn.php';
		}

	}

	public function get($array = array(), $table)
	{	
		if(is_array($array))
		{
			foreach($array as $key => $value)
			{
				if($this->select != '')
				{
					$this->select .= ", ";
				}

				if($value == '*')
				{
					$this->select = $value;
				}
				else
				{
					$this->select = "`" . $value . "`";
				}
			}
		}

		$this->get = "SELECT " . $this->select . " FROM " . $table . $this->where . $this->order . $this->limit;

		return $this->_run($this->get);
	}

	public function where($array = array())
	{
		if(is_array($array))
		{
			foreach($array as $key => $value)
			{
				if($this->where == '')
				{
					$this->where = " WHERE `" . $key . "`='" . $value . "' ";
				}
				else
				{
					$this->where .= "AND `" . $key . "`='" . $value . "' ";
				}
			}
		}
	}

	public function and_where($array = array())
	{
		$loops = count($array);
		$loop = 0;

		if(is_array($array))
		{
			$this->where .= "AND (";
				foreach($array as $key => $value)
				{
					$loop++;

					$this->where .= "`" . $key . "`='" . $value . "'";

					if($loops != 1 && $loops != $loop)
					{
						$this->where .= " AND ";
					}
				}
			$this->where .= ") ";
		}
	}

	public function or_where($array = array())
	{
		$loops = count($array);
		$loop = 0;

		if(is_array($array))
		{
			$this->where .= "OR (";
				foreach($array as $key => $value)
				{
					$loop++;

					$this->where .= "`" . $key . "`='" . $value . "'";

					if($loops != 1 && $loops != $loop)
					{
						$this->where .= " AND ";
					}
				}
			$this->where .= ") ";
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

	private function _run($query)
	{
		$this->result = mysql_query($query);
	}

}