<?php

class SQLQuery
{

	protected $_db_handle;
	protected $_result;

	function connect($address, $account, $password, $name)
	{

		$this->_db_handle = @mysql_connect($address, $account, $password);

		if($this->_db_handle != 0)
		{

			if(mysql_select_db($name, $this->_db_handle))
			{

				return 1;

			}
			else
			{

				return 0;

			}

		}
		else
		{

			return 0;

		}

	}

	function disconnect()
	{

		if(@mysql_close($this->_db_handle) != 0)
		{

			return 1;

		} else 
		{

			return 0;

		}

	}

	function select_all()
	{

		$query = 'SELECT * FROM `' . $this->_table . '`';

		return $this->query($query);

	}

	function select($value = array())
	{

		$value = '`' . implode('`, `', $value) . '`';

		$query = 'SELECT ' . $value . ' FROM `' . $this->_table . '`';

		return $this->query($query);

	}

	function query($query, $single_result = 0)
	{

		$this->_result = mysql_query($query, $this->_db_handle);

		if(preg_match("/SELECT/i", $query))
		{

			$result = array();
			$table = array();
			$field = array();
			$temp_results = array();
			$num_of_fields = mysql_num_fields($this->_result);

			for($i = 0; $i < $num_of_fields; $i++)
			{

				array_push($table, mysql_field_table($this->_result, $i));
				array_push($field, mysql_field_name($this->_result, $i));

			}

			while($row = mysql_fetch_row($this->_result))
			{

				for($i = 0; $i < $num_of_fields; $i++)
				{

					$table[$i] = trim(ucfirst($table[$i]), "s");
					$temp_results[$table[$i]][$field[$i]] = $row[$i];

				}

				if($single_result == 1) {

					mysql_free_result($this->_result);
					return $temp_results;

				}
				array_push($result, $temp_results);

			}
			mysql_free_result($this->_result);
			return ($result);

		}

	}

	function get_num_rows() 
	{

		return mysql_num_rows($this->_result);

	}

	function free_result() 
	{

		mysql_free_result($this->_result);

	}

	function get_error()
	{

		return mysql_error($this->_db_handle);

	}

}