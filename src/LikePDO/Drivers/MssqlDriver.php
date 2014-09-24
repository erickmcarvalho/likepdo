<?php
/**
 * LikePDO - The driver for Microsoft SQL Server
 * 
 * @name		PHP Like PDO
 * @author		Érick Carvalho <http://www.github.com/erickmcarvalho>
*/

namespace LikePDO\Drivers;

use LikePDO\LikePDO;
use LikePDO\LikePDOException;
use LikePDO\Interfaces\DriverInterface;

class MssqlDriver implements DriverInterface
{
	/**
	 * The connection
	 * 
	 * @access	private
	 * @var		resource
	*/
	private $mssql		= NULL;
	
	/**
	 * Driver options
	 * 
	 * @access	private
	 * @var		array
	*/
	private $options	= array
	(
		LikePDO::ATTR_PERSISTENT => FALSE
	);
	
	/**
	 * Creates a PDO instance to represent a connection to the requested database.
	 * 
	 * @param	array	$dsn - The Data Source Name, or DSN, contains the information required to connect to the database.
	 * (
	 * 		[host] => string - IP or host of SQL Server
	 * 		[port] => numeric - Port of SQL Serber
	 * 		[dbname] => string - Database name of SQL Server
	 * )
	 * @param	string	$username - The user name for the DSN string. This parameter is optional for some PDO drivers.
	 * @param	string	$password - The password for the DSN string. This parameter is optional for some PDO drivers.
	 * @param	array	$options - A key=>value array of driver-specific connection options.
	 * @return	object
	*/
	public function __construct(array $dsn, $username = NULL, $password = NULL, array $options = array())
	{
		if(isset($options[LikePDO::ATTR_PERSISTENT]))
			$this->options[LikePDO::ATTR_PERSISTENT] = $options[LikePDO::ATTR_PERSISTENT];
			
		if(!isset($dsn['host']))
			$dsn['host'] = "127.0.0.1";
			
		if(!$dsn['host'])
			$dsn['host'] = "127.0.0.1";
			
		if(isset($dsn['port']))
			if($dsn['port'])
				$dsn['host'] = (substr(strtoupper(PHP_OS), 0, 3) == "WIN" ? "," : ":").$dsn['port'];

		if(isset($dsn['charset']))
			ini_set("mssql.charset", $dsn['charset']);
		
		if($this->options[LikePDO::ATTR_PERSISTENT] == true)
			$this->mssql = mssql_pconnect($dsn['host'], $username, $password);
		else
			$this->mssql = mssql_connect($dsn['host'], $username, $password);
			
		if(!$this->mssql)
		{
			throw new LikePDOException("Failed to connect in SQL Server");
			return false;
		}
		elseif(isset($dsn['dbname']))
		{
			if($dsn['dbname'])
			{
				$db = mssql_select_db($dsn['dbname'], $this->mssql);
				
				if(!$db)
				{
					throw new LikePDOException("Failed to connect in ".$dsn['dbname']." database");
					return false;
				}
			}
		}
		
		return true;
	}
	
	/**
	 * Execute a query statement
	 * 
	 * @param	string	$statement - The query statement
	 * @return	boolean
	*/
	public function query($statement)
	{
		return mssql_query($statement, $this->mssql);
	}
	
	/**
	 * Fetches the next row from a result set
	 * 
	 * @param	resource	$statement - Query statement
	 * @param	integer		$fetch_style - Fetch type
	 * @return	mixed
	*/
	public function fetch($statement, $fetch_style = LikePDO::FETCH_ASSOC)
	{
		if(!is_resource($statement))
		{
			throw new LikePDOException("There is no active statement");
			return false;
		}
		else
		{
			switch($fetch_style)
			{
				case LikePDO::FETCH_ASSOC :
					return mssql_fetch_assoc($statement);
				break;
				case LikePDO::FETCH_NUM :
					return mssql_fetch_row($statement);
				break;
				case LikePDO::FETCH_OBJ :
					return mssql_fetch_object($statement);
				break;
			}
		}
	}
	
	/**
	 * Get field information
	 * 
	 * @param	resource	$statement - The result resource that is being evaluated.
	 * @param	integer		$field_offset - The numerical field offset. If the field offset is not specified, the next field that was not yet retrieved by this function is retrieved. The field_offset starts at 0.
	 * @return	object
	*/
	public function fetchField($statement, $field_offset = -1)
	{
		if(!is_resource($statement))
		{
			throw new LikePDOException("There is no active statement");
			return false;
		}
		else
		{
			return mssql_fetch_field($statement, $field_offset);
		}
	}
	
	/**
	 * Move the internal result pointer to the next result
	 * 
	 * @param	resource	$statement - The result resource that is being evaluated.
	 * @return	boolean
	*/
	public function nextResult($statement)
	{
		if(!is_resource($statement))
		{
			throw new LikePDOException("There is no active statement");
			return false;
		}
		else
		{
			return mssql_next_result($statement);
		}
	}
	
	/**
	 * Initiates a transaction
	 * 
	 * @return	boolean
	*/
	public function beginTransaction()
	{
		return $this->query("BEGIN TRANSACTION");
	}
	
	/**
	 * Commits a transaction
	 * 
	 * @return	void
	*/
	public function commitTransaction()
	{
		return $this->query("COMMIT TRANSACTION");
	}
	
	/**
	 * Rolls back a transaction
	 * 
	 * @return	boolean
	*/
	public function rollBackTransaction()
	{
		$this->query("ROLLBACK TRANSACTION");
	}
	
	/**
	 * Returns the ID of the last inserted row or sequence value
	 * 
	 * @param	string	$name - Name of the sequence object from which the ID should be returned.
	 * @return	mixed
	*/
	public function getLastInsertId($name = NULL)
	{
		$query = $this->query("SELECT SCOPE_IDENTITY() AS Result");
		
		if(!$query)
			return false;
			
		return intval($this->fetch($query, LikePDO::FETCH_OBJ)->Result);
	}
	
	/**
	 * Returns the number of records affected by the query
	 * 
	 * @return	integer
	*/
	public function getRowsAffected()
	{
		return mssql_rows_affected($this->mssql);
	}

	/**
	 * Gets the number of fields in result
	 * 
	 * @param	resource	$statement - The result resource that is being evaluated.
	 * @return	integer
	*/
	public function getNumFields($statement)
	{
		if(!is_resource($statement))
		{
			throw new LikePDOException("There is no active statement");
			return false;
		}
		else
		{
			return mssql_num_fields($statement);
		}
	}
	
	/**
	 * Quotes a string for use in a query.
	 * 
	 * @param	string	$string - The string to be quoted.
	 * @param	integer	$parameter_type - Provides a data type hint for drivers that have alternate quoting styles. [default -> LikePDO::PARAM_STR]
	 * @return	mixed
	*/
	public function quoteString($string, $parameter_type = LikePDO::PARAM_STR)
	{
		switch($parameter_type)
		{
			case LikePDO::PARAM_BOOL :
				return strval($string) == '1' || (is_bool($string) == true && $string == true) ? 1 : 0;
			break;
			case LikePDO::PARAM_NULL :
				return "NULL";
			break;
			case LikePDO::PARAM_INT :
				return intval($string);
			break;
			case LikePDO::PARAM_STR : default :
				return "'".$this->escapeString($string)."'";
			break;
		}
	}
	
	/**
	 * Fetch the SQLSTATE associated with the last operation on the database handle
	 * 
	 * @return	string	SQLSTATE, a five characters alphanumeric identifier defined in the ANSI SQL-92 standard.
	*/
	public function getSQLState()
	{
		return NULL;
	}
	
	/**
	 * Fetch the error code associated with the last operation on the database handle
	 * 
	 * @return	integer	Error Code
	*/
	public function getErrorCode()
	{
		return NULL;
	}
	
	/**
	 * Fetch extended error information associated with the last operation on the database handle
	 * 
	 * @return	array	Array of error information about the last operation performed by this database handle.
	*/
	public function getLastMessage()
	{
		return mssql_get_last_message();
	}

	/**
	 * Escapes special characters in a string for use in an SQL statement
	 * 
	 * @param	string	$string - The string to be escaped.
	 * @return	string
	*/
	public function escapeString($string)
	{
		return str_replace("'", "''", $string);
	}
}