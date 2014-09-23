<?php
/**
 * LikePDO - The PDO Class
 * Similar database library to PDO
 * 
 * @name		PHP Like PDO
 * @author		Érick Carvalho <http://www.github.com/erickmcarvalho>
*/

namespace LikePDO;

use LikePDO\Drivers;
use LikePDO\Interfaces;
use LikePDO\Instances;

class LikePDO implements LikePDOInterface
{
	/**
	 * Drivers
	 * 
	 * @access	private
	 * @var		array
	*/
	private $drivers				= array("Mssql");
	
	/**
	 * Driver Class Statement
	 * 
	 * @access	private
	 * @var		object
	*/
	private $driver					= NULL;
	
	/**
	 * DSN Settings
	 * 
	 * @access	private
	 * @var		object
	*/
	private $dsn					= array();
	
	/**
	 * Options
	 * 
	 * @access	private
	 * @var		array
	*/
	private $options				= array
	(
		self::ATTR_CASE => self::CASE_NATURAL,
		self::ATTR_DEFAULT_FETCH_MODE => self::FETCH_BOTH
	);
	
	/**
	 * In transaction
	 * 
	 * @access	private
	 * @var		boolean
	*/
	private $inTransaction			= FALSE;
	
	/**
	 * Support Transaction
	 * 
	 * @access	private
	 * @var		boolean
	*/
	private $supportTransaction		= FALSE;
	
	/**
	 * Creates a PDO instance to represent a connection to the requested database.
	 * 
	 * @param	string	$dsn - The Data Source Name, or DSN, contains the information required to connect to the database.
	 * @param	string	$username - The user name for the DSN string. This parameter is optional for some PDO drivers.
	 * @param	string	$password - The password for the DSN string. This parameter is optional for some PDO drivers.
	 * @param	array	$options - A key=>value array of driver-specific connection options.
	 * @return	object
	*/
	public function __construct($dsn, $username = NULL, $password = NULL, array $options = array())
	{
		if(substr($dsn, 0, 4) == "uri:")
		{
			if(function_exists("curl_init"))
			{
				try
				{
					$ch = curl_init(substr($dsn, 4));
					
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($ch, CURLOPT_TIMEOUT, 10);
					
					$exec = curl_exec($ch);
					
					if(curl_getinfo($ch, CURLINTO_HTTP_CODE) <> 500)
					{
						throw new LikePDOException("Failed to get URI ".substr($dsn, 4));
					}
					else
					{
						$dsn = trim($exec);
					}
					
					curl_close($ch);
				}
				catch(Exception $e)
				{
					exit($e->getMessage());
				}
			}
			else
			{
				throw new LikePDOException("cURL extension not installed");
			}
		}
		$tmp = $dsn;
		$dsn = NULL;
		
		list($driver, $dsn) = explode(":", $tmp);
		
		$dsn = explode(";", $dsn);
		$_dsn = array();
		
		$this->dsn['driver'] = $driver;
		array_shift($explode);
		
		if(count($dns) < 1)
		{
			throw new LikePDOException("DSN parameter invalid");
		}
		else
		{
			foreach($dsn as $row)
			{
				if(strstr($row, "="))
				{
					list($key, $value) = explode("=");
					
					$this->dsn[$key] = $value;
				}
			}
		}
		
		if(count($options) > 0)
		{
			foreach($options as $key => $value)
			{
				$this->options[$key] = $value;
			}
		}
		
		switch($this->dsn['driver'])
		{
			case "mssql" :
				$this->driver = new MssqlDriver($this->dsn, $username, $password, $options);
			break;
		}
	}
	
	/**
	 * Initiates a transaction
	 * 
	 * @return	boolean
	*/
	public function beginTransaction()
	{
		if($this->supportTransaction == false)
		{
			throw new LikePDOException("This driver doesn't support transactions");
			return false;
		}
		elseif($this->inTransaction == true)
		{
			throw new LikePDOException("There is already an active transaction");
			return false;
		}
		else
		{
			if(!$this->driver->beginTransaction())
			{
				throw new LikePDOException("Failed to begin transaction");
				return false;
			}
			
			return true;
		}
	}
	
	/**
	 * Commits a transaction
	 * 
	 * @return	boolean
	*/
	public function commit()
	{
		if($this->inTransaction == true)
		{
			throw new LikePDOException("There is no active transaction");
			return false;
		}
		else
		{
			if(!$this->driver->commitTransaction())
			{
				throw new LikePDOException("Failed to commit transaction");
				return false;
			}
			
			return true;
		}
	}
	
	/**
	 * Fetch the SQLSTATE associated with the last operation on the database handle
	 * 
	 * @return	string	SQLSTATE, a five characters alphanumeric identifier defined in the ANSI SQL-92 standard.
	*/
	public function errorCode()
	{
		return $this->driver->getSQLState();
	}
	
	/**
	 * Fetch extended error information associated with the last operation on the database handle
	 * 
	 * @return	array	Array of error information about the last operation performed by this database handle.
	*/
	public function errorInfo()
	{
		return array
		(
			0 => $this->driver->getSQLState(),
			1 => $this->driver->getErrorCode(),
			2 => $this->driver->getLastMessage()
		);
	}
	
	/**
	 * Execute an SQL statement and return the number of affected rows
	 * 
	 * @param	string	$statement - The SQL statement to prepare and execute.
	 * @return	integer	Rows that were modified or deleted by the SQL statement you issued.
	*/
	public function exec($statement)
	{
		if($this->driver->query($statement))
		{
			return $this->driver->getRowsAffected();
		}
		else
		{
			throw new LikePDOException("Failed to execute the query ".$statement);
			return false;
		}
	}
	
	/**
	 * Retrieve a database connection attribute
	 * 
	 * @param	integer	$attribute - One of the PDO::ATTR_* constants.
	 * @return	mixed	A successful call returns the value of the requested PDO attribute. An unsuccessful call returns null.
	*/
	public function getAttribute($attribute)
	{
		if(isset($this->options[$attribute]))
			return $this->options[$attribute];
	}
	
	/**
	 * Return an array of available LikePDO drivers
	 * 
	 * @return	array
	*/
	public function getAvailableDrivers()
	{
		$drivers = array();
		
		if(count($this->drivers) > 0)
		{
			foreach($this->drivers as $driver)
			{
				if(class_exists("LikePDO\\Drivers\\".$driver."Driver"))
				{
					$drivers[] = $driver;
				}
			}
		}
		
		return $drivers;
	}
	
	/**
	 * Checks if inside a transaction
	 * 
	 * @return	boolean
	*/
	public function inTransaction()
	{
		return $this->inTransaction;
	}
	
	/**
	 * Returns the ID of the last inserted row or sequence value
	 * 
	 * @param	string	$name - Name of the sequence object from which the ID should be returned. [default -> NULL]
	 * @return	mixed
	*/
	public function lastInsertId($name = NULL)
	{
		return strval($this->driver->getLastInsertId($name));
	}
	
	/**
	 * Prepares a statement for execution and returns a statement object
	 * http://php.net/manual/pt_BR/pdo.prepare.php
	 * 
	 * @param	string	$statement - This must be a valid SQL statement for the target database server.
	 * @param	array	$driver_options - This array holds one or more key=>value pairs to set attribute values for the PDOStatement object that this method returns.
	 * @return	object	instanceof LikePDOStatement
	*/
	public function prepare($statement, $driver_options = array())
	{
		return new LikePDOStatement($statement, $this);
	}
	
	/**
	 * Executes an SQL statement, returning a result set as a LikePDOStatement object
	 * 
	 * @param	string	$statement - The SQL statement to prepare and execute.
	 * @param	integer	$fetch_type - LikePDO::FETCH_COLUMN / LikePDO::FETCH_COLUMN / LikePDO::FETCH_INTO [default -> NULL]
	 * @param	mixed	$fetch_arga - [int $colno] / [string $classname] / [object $object]
	 * @param	mixed	$fetch_argb - [array $ctorargs]
	*/
	public function query($statement, $fetch_type = NULL, $fetch_arga = NULL, $fetch_argb = NULL)
	{
		$statement = new LikePDO($statement, $this);
		$statement->setFetchMode($fetch_type, $fetch_arga, $fetch_argb);
		$statement->execute();
		
		return $statement->fetchAll();
	}
	
	/**
	 * Quotes a string for use in a query.
	 * 
	 * @param	string	$string - The string to be quoted.
	 * @param	integer	$parameter_type - Provides a data type hint for drivers that have alternate quoting styles. [default -> LikePDO::PARAM_STR]
	 * @return	mixed
	*/
	public function quote($string, $parameter_type = self::PARAM_STR)
	{
		return $this->driver->quoteString($string, $parameter_type);
	}
	
	/**
	 * Rolls back a transaction
	 * 
	 * @return	boolean
	*/
	public function rollBack()
	{
		if($this->inTransaction == true)
		{
			throw new LikePDOException("There is no active transaction");
			return false;
		}
		else
		{
			if(!$this->driver->rollBackTransaction())
			{
				throw new LikePDOException("Failed to roll back transaction");
				return false;
			}
			
			return true;
		}
	}
	
	/**
	 * Set an attribute
	 * 
	 * @param	integer	$attribute - The attribute name
	 * @param	mixed	$value - The attribute value
	 * @return	boolean
	*/
	public function setAttribute($attribute, $value)
	{
		$this->options[$attribute] = $value;
	}
}