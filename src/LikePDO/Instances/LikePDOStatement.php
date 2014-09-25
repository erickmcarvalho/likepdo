<?php
/**
 * LikePDO - The Statement Class
 * 
 * @name		PHP Like PDO
 * @author		Érick Carvalho <http://www.github.com/erickmcarvalho>
*/

namespace LikePDO\Instances;

use LikePDO\LikePDO;
use LikePDO\LikePDOException;
use LikePDO\Instances\LikePDORow;
use ReflectionClass;
use stdClass;

class LikePDOStatement
{
	/**
	 * LikePDO Class
	 * 
	 * @access	private
	 * @var		object
	*/
	private $pdo					= NULL;
	
	/**
	 * Used query string.
	 * 
	 * @access	private
	 * @var		string
	*/
	private $queryString			= NULL;
	
	/**
	 * Query resource
	 * 
	 * @access	private
	 * @var		resource
	*/
	private $resource				= NULL;
	
	/**
	 * Columns bound
	 * 
	 * @access	private
	 * @var		array
	*/
	private $columnsBound			= array();
	
	/**
	 * Parameters bound
	 * 
	 * @access	private
	 * @var		array
	*/
	private $parametersBound		= array();
	
	/**
	 * Is fetched
	 * 
	 * @access	private
	 * @var		boolean
	*/
	private $isFetched				= FALSE;
	
	/**
	 * The number of rows affected by the last DELETE, INSERT, or UPDATE statement executed by the corresponding LikePDOStatement object.
	 * 
	 * @access	private
	 * @var		integer
	*/
	private $rowsAffected			= 0;
	
	/**
	 * Column count
	 * 
	 * @access	private
	 * @var		integer
	*/
	private $columnCount			= 0;
	
	/**
	 * Columns fetched
	 * 
	 * @access	private
	 * @var		array
	*/
	private $columns				= array();
	
	/**
	 * Parameters
	 * 
	 * @access	private
	 * @var		array
	*/
	private $parameters				= array();
	
	/**
	 * Error code
	 * 
	 * @access	private
	 * @var		mixed
	*/
	private $errorCode				= NULL;
	
	/**
	 * Error info
	 * 
	 * @access	private
	 * @var		string
	*/
	private $errorInfo				= NULL;
	
	/**
	 * Statement executed
	 * 
	 * @access	private
	 * @var		boolean
	*/
	private $executed				= FALSE;
	
	/**
	 * Statement execute failed
	 * 
	 * @access	private
	 * @var		boolean
	*/
	private $failed					= FALSE;
	
	/**
	 * Fetch mode
	 * 
	 * @access	private
	 * @var		integer
	*/
	public $fetchMode				= LikePDO::FETCH_ASSOC;
	
	/**
	 * Fetch mode definition
	 * 
	 * @access	private
	 * @var		mixed
	*/
	private $fetchModeDefinition	= NULL;
	
	/**
	 * Fetch mode arguments
	 * 
	 * @access	private
	 * @var		array
	*/
	private $fetchModeArguments		= array();
	
	/**
	 * Result fetch
	 * 
	 * @access	private
	 * @var		mixed
	*/
	private $fetch					= NULL;
	
	/**
	 * Result fetch for columns by offset
	 * 
	 * @access	private
	 * @var		array
	*/
	private $fetchColumn			= array();
	
	/**
	 * Fetch result mode
	 * 
	 * @access	private
	 * @var		mixed
	*/
	private $fetchResultMode		= NULL;
	
	/**
	 * Is fetch all
	 * 
	 * @access	private
	 * @var		boolean
	*/
	private $isFetchAll				= FALSE;
	
	/**
	 * Create a PDO query statement
	 * 
	 * @param	string	$statement - Query statement
	 * @return	void
	*/
	public function __construct($statement, LikePDO $pdo)
	{
		$this->queryString = $statement;
		$this->pdo = $pdo;
	}
	
	/**
	 * Bind a column to a PHP variable
	 * 
	 * @param	mixed	$column - Number of the column (1-indexed) or name of the column in the result set.
	 * @param	mixed	&$param - Name of the PHP variable to which the column will be bound.
	 * @param	integer	$type - Data type of the parameter, specified by the PDO::PARAM_* constants.
	 * @param	integer	$maxlen - A hint for pre-allocation.
	 * @param	mixed	$driverdata - Optional parameter(s) for the driver.
	 * @return	boolean
	*/
	public function bindColumn($column, &$param, $type = NULL, $maxlen = 0, $driverdata = NULL)
	{
		if(!is_resource($this->resource))
		{
			throw new LikePDOException("There is no active statement");
			return false;
		}
		else
		{
			$this->columnsBound[$column] = array
			(
				"param" => &$param,
				"type" => $type,
				"maxlen" => $maxlen
			);
			
			return true;
		}
	}
	
	/**
	 * Binds a parameter to the specified variable name
	 * 
	 * @param	mixed	$parameter - Parameter identifier. For a prepared statement using named placeholders, this will be a parameter name of the form :name. For a prepared statement using question mark placeholders, this will be the 1-indexed position of the parameter.
	 * @param	mixed	&$variable - Name of the PHP variable to bind to the SQL statement parameter.
	 * @param	integer	$data_type - Explicit data type for the parameter using the PDO::PARAM_* constants. To return an INOUT parameter from a stored procedure, use the bitwise OR operator to set the PDO::PARAM_INPUT_OUTPUT bits for the data_type parameter.
	 * @param	integer	$length - Length of the data type. To indicate that a parameter is an OUT parameter from a stored procedure, you must explicitly set the length.
	 * @param	mixed	$driver_options
	 * @return	boolean
	*/
	public function bindParam($parameter, &$variable, $data_type = LikePDO::PARAM_STR, $length = 0, $driver_options = NULL)
	{
		if(is_resource($this->resource))
		{
			throw new LikePDOException("There is executed statement");
			return false;
		}
		else
		{
			$this->parametersBound[$parameter] = array
			(
				"variable" => &$variable,
				"data_type" => $data_type,
				"length" => $length
			);
			
			return true;
		}
	}
	
	/**
	 * Binds a value to a parameter
	 * 
	 * @param	mixed	$parameter - Parameter identifier. For a prepared statement using named placeholders, this will be a parameter name of the form :name. For a prepared statement using question mark placeholders, this will be the 1-indexed position of the parameter.
	 * @param	mixed	$value - The value to bind to the parameter.
	 * @param	integer	$data_type - Explicit data type for the parameter using the PDO::PARAM_* constants.
	 * @return	boolean
	*/
	public function bindValue($parameter, $value, $data_type = LikePDO::PARAM_STR)
	{
		if(is_resource($this->resource))
		{
			throw new LikePDOException("There is executed statement");
			return false;
		}
		else
		{
			$this->parameters[$parameter] = array
			(
				"data_type" => $data_type,
				"value" => $value
			);
			
			return true;
		}
	}
	
	/**
	 * Closes the cursor, enabling the statement to be executed again.
	 * 
	 * @return	boolean
	*/
	public function closeCursor()
	{
		if($this->isFetched == false)
		{
			throw new LikePDOException("There is no fetch statement");
			return false;
		}
		else
		{
			do
			{
				while($this->fetch());
					
				if(!$this->nextRowset())
					break;
					
			} while(true);
			
			return true;
		}
	}
	
	/**
	 * Returns the number of columns in the result set
	 * 
	 * @return	integer
	*/
	public function columnCount()
	{
		return intval($this->columnCount);
	}
	
	/**
	 * Dump an SQL prepared command
	 * 
	 * @return	void
	*/
	public function debugDumpParams()
	{
		if(!is_resource($this->resource))
		{
			throw new LikePDOException("There is no active statement");
			return false;
		}
		else
		{
			$parameters = array();
			
			if(count($this->parametersBound) > 0)
			{
				foreach($this->parametersBound as $key => $param)
				{
					if(!isset($this->parameters[$key]))
					{
						$parameters[] = array
						(
							"key" => is_string($key) == true ? "Name: [".strlen($key)."] ".$key : "Position #".$key,
							"paramno" => is_string($key) == true ? -1 : $key,
							"name" => is_string($key) == true ? "[".strlen($key)."] \"".$key."\"" : "[0] \"\"",
							"is_param" => 1,
							"param_type" => $param['data_type']
						);
					}
				}
			}
			
			if(count($this->parameters) > 0)
			{
				foreach($this->parameters as $key => $param)
				{
					if(!isset($this->parametersBound[$key]))
					{
						$parameters[] = array
						(
							"key" => is_string($key) == true ? "Name: [".strlen($key)."] ".$key : "Position #".$key.":",
							"paramno" => is_string($key) == true ? -1 : $key,
							"name" => is_string($key) == true ? "[".strlen($key)."] \"".$key."\"" : "[0] \"\"",
							"is_param" => 1,
							"param_type" => $param['data_type']
						);
					}
				}
			}
			
			printf("SQL: [%d] %s".PHP_EOL."Params:  %d".PHP_EOL.PHP_EOL, strlen($this->queryString), $this->queryString, count($parameters));
			
			if(count($parameters) > 0)
			{
				foreach($parameters as $param)
				{
					printf("Key: %s".PHP_EOL, $param['key']);
					printf("paramno=%d".PHP_EOL, $param['paramno']);
					printf("name=%s".PHP_EOL, $param['name']);
					printf("is_param=%d".PHP_EOL, $param['is_param']);
					printf("param_type=%d".PHP_EOL, $param['param_type']);
				}
			}
			
			return true;
		}
	}
	
	/**
	 * Fetch the SQLSTATE associated with the last operation on the statement handle
	 * 
	 * @return	mixed
	*/
	public function errorCode()
	{
		return $this->errorCode;
	}
	
	/**
	 * Fetch extended error information associated with the last operation on the statement handle
	 * 
	 * @return	mixed
	*/
	public function errorInfo()
	{
		return $this->errorInfo;
	}
	
	/**
	 * Executes a prepared statement
	 * 
	 * @param	array	$input_parameters - An array of values with as many elements as there are bound parameters in the SQL statement being executed. All values are treated as PDO::PARAM_STR.
	 * @return	boolean
	*/
	public function execute(array $input_parameters = array())
	{
		$queryString = $this->queryString;
		
		if(count($input_parameters) > 0)
		{
			if(count($this->parameters) > 0 || count($this->parametersBound) > 0)
			{
				throw new LikePDOException("There is have parameters bound");
				return false;
			}
			else
			{
				foreach($input_parameters as $key => $param)
				{
					$value = is_int($param) ? intval($param) : $this->pdo->quote($param, LikePDO::PARAM_STR);
					
					if(is_int($key))
					{
						$position = strpos($queryString, "?");
						
						if(!strstr($queryString, "?"))
						{
							throw new LikePDOException("Statement: Too few parameters");
							return false;
						}
						
						$queryString = substr_replace($queryString, $value, $position, 1);
					}
					else
					{
						$queryString = str_replace($key, $value, $queryString);
					}
				}
			}
		}
		
		if(count($this->parametersBound) > 0)
		{
			foreach($this->parametersBound as $key => $param)
			{
				$value = $this->pdo->quote($param['variable'], $param['data_type']);
				
				if(is_int($key))
				{
					$position = strpos($queryString, "?");
					
					if(!strstr($queryString, "?"))
					{
						throw new LikePDOException("Statement: Too few parameters");
						return false;
					}
					
					$queryString = substr_replace($queryString, $value, $position, 1);
				}
				else
				{
					$queryString = str_replace($key, $value, $queryString);
				}
			}
		}
		
		if(count($this->parameters) > 0)
		{
			foreach($this->parameters as $key => $param)
			{
				$value = $this->pdo->quote($param['value'], $param['data_type']);
				
				if(is_int($key))
				{
					$position = strpos($queryString, "?");
					
					if(!strstr($queryString, "?"))
					{
						throw new LikePDOException("Statement: Too few parameters");
						return false;
					}
					
					$queryString = substr_replace($queryString, $value, $position, 1);
				}
				else
				{
					$queryString = str_replace($key, $value, $queryString);
				}
			}
		}
		
		$this->resource = $this->pdo->driver->query($queryString);
		$this->executed = true;
		$this->rowsAffected = $this->pdo->driver->getRowsAffected();
		$this->columnCount = $this->pdo->driver->getNumFields($this->resource);
		
		if(!$this->resource)
		{
			$this->failed = true;
			
			$this->errorCode = $this->pdo->errorCode();
			$this->errorInfo = $this->pdo->errorInfo();
			
			new LikePDOException("Failed to execute the query ".$this->queryString);
			
			return false;
		}
		
		return true;
	}
	
	/**
	 * Fetches the next row from a result set
	 * 
	 * @param	integer	$fetch_style - Controls how the next row will be returned to the caller. This value must be one of the PDO::FETCH_* constants, defaulting to value of PDO::ATTR_DEFAULT_FETCH_MODE (which defaults to PDO::FETCH_BOTH).
	 * @param	integer	$cursor_orientation - For a LikePDOStatement object representing a scrollable cursor, this value determines which row will be returned to the caller.
	 * @param	integer	$cursor_offset - For a LikePDOStatement object representing a scrollable cursor for which the cursor_orientation parameter is set to PDO::FETCH_ORI_ABS, this value specifies the absolute number of the row in the result set that shall be fetched.
	 * @return	mixed
	*/
	public function fetch($fetch_style = NULL, $cursor_orientation = LikePDO::FETCH_ORI_NEXT, $cursor_offset = 0)
	{
		if($this->executed == true)
		{
			if(!$this->resource)
			{
				return false;
			}
			elseif($this->isFetchAll == true)
			{
				throw new LikePDOStatement("There is no active statement");
				return false;
			}
		}
		else
		{
			throw new LikePDOStatement("There is no active statement");
			return false;
		}
		
		if(!$fetch_style)
			$fetch_style = $this->pdo->options[LikePDO::ATTR_DEFAULT_FETCH_MODE];

		$columns = array();
		$fetch = NULL;
		
		switch($fetch_style)
		{
			case LikePDO::FETCH_ASSOC :
				$fetch = $this->pdo->driver->fetch($this->resource, LikePDO::FETCH_ASSOC);
				$result = array();
				
				if(count($fetch) > 0 && $fetch)
				{
					foreach($fetch as $key => $value)
					{
						$key = $this->realColumnAttribute($key);
						
						$result[$key] = $value;
						$columns[] = $key;
					}
				}
				
				$fetch = (array)$result;
			break;
			case LikePDO::FETCH_BOTH :
				$fetch = $this->pdo->driver->fetch($this->resource, LikePDO::FETCH_ASSOC);
				$result = array();
				$i = 0;
				
				if(count($fetch) > 0 && $fetch)
				{
					foreach($fetch as $key => $value)
					{
						$key = $this->realColumnAttribute($key);
						
						if(!isset($result[$key]))
							$result[$key] = $value;
							
						$result[$i++] = $value;
						
						if(is_string($key))
							$columns[] = $key;
					}
				}
				
				$fetch = (array)$result;
			break;
			case LikePDO::FETCH_BOUND :
				$fetch = $this->pdo->driver->fetch($this->resource, LikePDO::FETCH_ASSOC);
				$i = 0;
				
				if(count($fetch) > 0 && $fetch)
				{
					foreach($fetch as $key => $value)
					{
						$key = $this->realColumnAttribute($key);
								
						if(isset($this->columnsBound[$key]))
							$this->columnsBound[$key]['param'] = $value;
						elseif(isset($this->columnsBound[$i]))
							$this->columnsBound[$i]['param'] = $value;
						
						if(is_string($key))
							$columns[] = $key;
							
						$i++;
					}
				}
				
				$fetch = true;
			break;
			case LikePDO::FETCH_CLASS :
				$fetch = $this->pdo->driver->fetch($this->resource, LikePDO::FETCH_OBJ);
				
				if($this->fetchMode != LikePDO::FETCH_CLASS)
				{
					throw new LikePDOException("No fetch class specified");
					return false;
				}
				elseif(!$this->fetchModeDefinition)
				{
					throw new LikePDOException("No fetch class specified");
					return false;
				}
				elseif(!class_exists($this->fetchModeDefinition))
				{
					throw new LikePDOException("No fetch class specified");
					return false;
				}
				else
				{
					$instance = new ReflectionClass($this->fetchModeDefinition);
					$instance = $instance->newInstanceArgs($this->fetchModeArguments);
					
					if(count($fetch) > 0 && $fetch)
					{
						foreach($fetch as $key => $value)
						{
							$key = $this->realColumnAttribute($key);
								
							$columns[] = $key;
							$instance->{$key} = $value;
						}
					}
				}
				
				$fetch = $instance;
			break;
			case LikePDO::FETCH_INTO :
				$fetch = $this->pdo->driver->fetch($this->resource, LikePDO::FETCH_OBJ);
				
				if($this->fetchMode != LikePDO::FETCH_INTO)
				{
					throw new LikePDOException("No fetch-into object specified");
					return false;
				}
				elseif(!$this->fetchModeDefinition)
				{
					throw new LikePDOException("No fetch-into object specified");
					return false;
				}
				elseif(!is_object($this->fetchModeDefinition))
				{
					throw new LikePDOException("No fetch-into object specified");
					return false;
				}
				else
				{
					if(count($fetch) > 0 && $fetch)
					{
						foreach($fetch as $key => $value)
						{
							$key = $this->realColumnAttribute($key);
								
							$columns[] = $key;
							$this->fetchModeDefinition->{$key} = $value;
						}
					}
				}
				
				$fetch = $this->fetchModeDefinition;
			break;
			case LikePDO::FETCH_LAZY :
				$fetch = $this->pdo->driver->fetch($this->resource, LikePDO::FETCH_ASSOC);
				
				$LikePDORow = new LikePDORow();
				$LikePDORow->queryString = $this->queryString;
				
				if(count($fetch) > 0 && $fetch)
				{
					foreach($fetch as $key => $value)
					{
						$key = $this->realColumnAttribute($key);
								
						$columns[] = $key;
						$LikePDORow->{$key} = $value;
					}
				}
				
				$fetch = $LikePDORow;
			break;
			case LikePDO::FETCH_NAMED :
				$fetch = $this->pdo->driver->fetch($this->resource, LikePDO::FETCH_NUM);
				$result = array();

				if(count($fetch) > 0 && $fetch)
				{
					foreach($fetch as $key => $value)
					{
						$field = $this->pdo->driver->fetchField($this->resource, $key);
						$field = $this->realColumnAttribute($field->name);
						
						if(in_array($field, $columns))
						{
							if(is_array($result[$field]))
							{
								$result[$field][] = $value;
							}
							else
							{
								$tmp = $result[$field];
								$result[$field] = array($tmp, $value);

								unset($tmp);
							}
						}
						else
						{
							$result[$field] = $value;
						}
						
						$columns[] = $field;
					}
				}
				
				$fetch = (array)$result;
			break;
			case LikePDO::FETCH_NUM :
				$fetch = $this->pdo->driver->fetch($this->resource, LikePDO::FETCH_NUM);
				
				if(count($fetch) > 0 && $fetch)
				{
					foreach($fetch as $key => $value)
					{
						$field = $this->pdo->driver->fetchField($this->resource);
						$key = $this->realColumnAttribute($key);
						
						$columns[] = $field->name;
					}
				}
			break;
			case LikePDO::FETCH_OBJ :
				$fetch = $this->pdo->driver->fetch($this->resource, LikePDO::FETCH_OBJ);
				$result = new stdClass();
				
				if(count($fetch) > 0 && $fetch)
				{
					foreach($fetch as $key => $value)
					{
						$key = $this->realColumnAttribute($key);
							
						$result->{$key} = $value;
						$columns[] = $key;
					}
				}
				
				$fetch = (object)$result;
			break;
			default :
				throw new LikePDOException("Invalid fetch mode");
				return false;
			break;
		}
		
		$this->fetch = $fetch;
		$this->columns = $columns;
		$this->fetchResultMode = $fetch_style;
		$this->isFetchAll = false;
		
		unset($result, $LikePDORow, $instance, $fetch, $columns);
		return $this->fetch;
	}
	
	/**
	 * Returns an array containing all of the result set rows
	 * 
	 * @param	integer	$fetch_style - Controls the contents of the returned array as documented in LikePDOStatement::fetch().
	 * @param	integer	$cursor_orientation - For a LikePDOStatement object representing a scrollable cursor, this value determines which row will be returned to the caller.
	 * @param	integer	$cursor_offset - For a LikePDOStatement object representing a scrollable cursor for which the cursor_orientation parameter is set to PDO::FETCH_ORI_ABS, this value specifies the absolute number of the row in the result set that shall be fetched.
	 * @param	mixed	$final_arga
	 * @param	mixed	$final_argb
	 * @return	mixed
	*/
	public function fetchAll($fetch_style = NULL, $cursor_orientation = LikePDO::FETCH_ORI_NEXT, $cursor_offset = 0, $final_arga = NULL, $final_argb = NULL)
	{
		if($this->executed == true)
		{
			if(!$this->resource)
			{
				return false;
			}
			elseif($this->isFetched == true)
			{
				throw new LikePDOStatement("There is no active statement");
				return false;
			}
		}
		else
		{
			throw new LikePDOStatement("There is no active statement");
			return false;
		}
		
		if(!$fetch_style)
			$fetch_style = $this->pdo->options[LikePDO::ATTR_DEFAULT_FETCH_MODE];

		$columns = array();
			
		switch($fetch_style)
		{
			case LikePDO::FETCH_ASSOC :
				$result = array();
				$i = 0;
				
				while($fetch = $this->pdo->driver->fetch($this->resource, LikePDO::FETCH_ASSOC))
				{
					$result[$i] = array();
					
					foreach($fetch as $key => $value)
					{
						$key = $this->realColumnAttribute($key);						
						$result[$i][$key] = $value;
					}
					
					$i++;
				}
				
				$fetch = (array)$result;
			break;
			case LikePDO::FETCH_BOTH :
				$result = array();
				$i = 0;
				
				while($fetch = $this->pdo->driver->fetch($this->resource, LikePDO::FETCH_ASSOC))
				{
					$result[$i] = array();
					$t = 0;
					
					foreach($fetch as $key => $value)
					{
						$key = $this->realColumnAttribute($key);
							
						if(!isset($result[$i][$key]))
							$result[$i][$key] = $value;
							
						$result[$i][$t++] = $value;
					}
					
					$i++;
				}
				
				$fetch = (array)$fetch;
			break;
			case LikePDO::FETCH_BOUND :
				$fetch = $this->pdo->driver->fetch($this->resource, LikePDO::FETCH_ASSOC);
				
				if(count($fetch) > 0)
				{
					foreach($fetch as $key => $value)
					{
						$key = $this->realColumnAttribute($key);
								
						if(isset($this->columnsBound[$key]))
							$this->columnsBound[$key]['param'] = $value;
						elseif(isset($this->columnsBound[$i]))
							$this->columnsBound[$i]['param'] = $value;
						
						$i++;
					}
				}
				
				$fetch = true;
			break;
			case LikePDO::FETCH_CLASS :
				$fetch = false;
				
				if(is_string($cursor_orientation))
				{
					$class = $cursor_orientation;
					$arguments = array();
				
					if(is_array($cursor_offset))
					{
						$arguments = $cursor_offset;
						$cursor_orientation = $final_arga;
						$cursor_offset = $final_argb;
					}
					else
					{
						$cursor_orientation = $cursor_offset;
						$cursor_offset = $final_arg;
					}
				}
				else
				{
					if($this->fetchModeDefinition)
					{
						if(!class_exists($this->fetchModeDefinition))
						{
							throw new LikePDOException("No fetch class specified");
							return false;
						}
						else
						{
							$class = $this->fetchModeDefinition;
							$arguments = $this->fetchModeArguments;
						}
					}
					else
					{
						$class = "stdClass";
						$arguments = array();
					}
				}
				
				if(!class_exists($class))
				{
					throw new LikePDOException("No fetch class specified");
					return false;
				}
				else
				{
					$result = array();
					$i = 0;
					
					while($fetch = $this->pdo->driver->fetch($this->resource, LikePDO::FETCH_OBJ))
					{
						$instance = new ReflectionClass($class);
						$result[$i] = $instance->newInstanceArgs($arguments);
						
						foreach($fetch as $key => $value)
						{
							$key = $this->realColumnAttribute($key);
							$result[$i]->{$key} = $value;
						}
						
						$i++;
					}
					
					$fetch = $result;
				}
			break;
			case LikePDO::FETCH_INTO :
				$fetch = false;
				
				if($this->fetchMode != LikePDO::FETCH_INTO)
				{
					throw new LikePDOException("No fetch-into object specified");
					return false;
				}
				elseif(!$this->fetchModeDefinition)
				{
					throw new LikePDOException("No fetch-into object specified");
					return false;
				}
				elseif(!is_object($this->fetchModeDefinition))
				{
					throw new LikePDOException("No fetch-into object specified");
					return false;
				}
				else
				{
					$result = array();
					$i = 0;
					
					while($fetch = $this->pdo->driver->fetch($this->resource, LikePDO::FETCH_OBJ))
					{
						foreach($fetch as $key => $value)
						{
							$key = $this->realColumnAttribute($key);
							$this->fetchModeDefinition->{$key} = $value;
						}
						
						$result[$i++] = $this->fetchModeDefinition;
					}
					
					$fetch = $result;
				}
			break;
			case LikePDO::FETCH_LAZY :
				throw new LikePDOException("PDO::FETCH_LAZY can't be used with LikePDOStatement::fetchAll()");
				return false;
			break;
			case LikePDO::FETCH_NAMED :
				$result = array();
				$i = 0;
				
				while($fetch = $this->pdo->driver->fetch($this->resource, LikePDO::FETCH_NUM))
				{
					foreach($fetch as $key => $value)
					{
						$field = $this->pdo->driver->fetchField($this->resource, $key);
						$field = $this->realColumnAttribute($field->name);
							
						if(isset($result[$i][$field]))
						{
							if(is_array($result[$i][$field]))
							{
								$result[$i][$field][] = $value;
							}
							else
							{
								$tmp = $result[$i][$field];
								$result[$i][$field] = array($tmp, $value);

								unset($tmp);
							}
						}
						else
						{
							$result[$i][$field] = $value;
						}
					}
					
					$i++;
				}
				
				$fetch = (array)$result;
			break;
			case LikePDO::FETCH_NUM :
				$result = array();
			
				while($fetch = $this->pdo->driver->fetch($this->resource, LikePDO::FETCH_NUM))
				{
					$result[] = (array)$fetch;
				}
				
				$fetch = (array)$result;
			break;
			case LikePDO::FETCH_OBJ :
				$result = array();
				$i = 0;
				
				while($fetch = $this->pdo->driver->fetch($this->resource, LikePDO::FETCH_OBJ))
				{
					$result[$i] = new stdClass();
					
					foreach($fetch as $key => $value)
					{
						$key = $this->realColumnAttribute($key);
						$result[$i]->{$key} = $value;
					}
					
					$i++;
				}
				
				$fetch = (object)$result;
			break;
			default :
				throw new LikePDOException("Invalid fetch mode");
				return false;
			break;
		}
		
		$this->fetch = $fetch;
		$this->fetchResultMode = $fetch_style;
		$this->isFetchAll = true;
		
		unset($result, $LikePDORow, $instance, $fetch);
		return $this->fetch;
	}
	
	/**
	 * Returns a single column from the next row of a result set
	 * 
	 * @param	integer	$column_number - 0-indexed number of the column you wish to retrieve from the row.
	 * @return	mixed
	*/
	public function fetchColumn($column_number = 0)
	{
		if($this->executed == true)
		{
			if(!$this->resource && $this->isFetched == false)
			{
				return false;
			}
		}
		else
		{
			throw new LikePDOStatement("There is no active statement");
			return false;
		}
		
		$result = NULL;
		
		if($this->isFetched == true)
		{
			if($this->isFetchAll == true)
			{
				return false;
			}
			else
			{
				if(isset($this->columns[$column_number]))
				{
					switch($this->fetchResultMode)
					{
						case LikePDO::FETCH_ASSOC :
							if(isset($this->fetch[$this->columns[$column_number]]))
							{
								return $this->fetch[$this->columns[$column_number]];
							}
						break;
						case LikePDO::FETCH_BOTH :
							if(isset($this->fetch[$this->columns[$column_number]]))
							{
								return $this->fetch[$this->columns[$column_number]];
							}
						break;
						case LikePDO::FETCH_BOUND :
							if(!$this->fetchColumn)
								$this->fetchColumn = $this->pdo->driver->fetch($this->resource, LikePDO::FETCH_NUM);
							
							if(isset($this->fetchColumn[$column_number]))
								return $this->fetchColumn[$column_number];
						break;
						case LikePDO::FETCH_CLASS :
							if(isset($this->fetch->{$this->columns[$column_number]}))
								return $this->fetch->{$this->columns[$column_number]};
						break;
						case LikePDO::FETCH_INTO :
							if(isset($this->fetch->{$this->columns[$column_number]}))
								return $this->fetch->{$this->columns[$column_number]};
						break;
						case LikePDO::FETCH_LAZY :
							if(isset($this->fetch->{$this->columns[$column_number]}))
								return $this->fetch->{$this->columns[$column_number]};
						break;
						case LikePDO::FETCH_NAMED :
							if(!$this->fetchColumn)
								$this->fetchColumn = $this->pdo->driver->fetch($this->resource, LikePDO::FETCH_NUM);
							
							if(isset($this->fetchColumn[$column_number]))
								return $this->fetchColumn[$column_number];
						break;
						case LikePDO::FETCH_NUM :
							if(isset($this->fetch[$column_number]))
								return $this->fetch[$column_number];
						break;
						case LikePDO::FETCH_OBJ :
							if(isset($this->fetch->{$this->columns[$column_number]}))
								return $this->fetch->{$this->columns[$column_number]};
						break;
					}
				}
			}
		}
		else
		{
			if(!$this->fetchColumn)
				$this->fetchColumn = $this->pdo->driver->fetch($this->resource, LikePDO::FETCH_NUM);
							
			if(isset($this->fetchColumn[$column_number]))
				return $this->fetchColumn[$column_number];
		}
		
		return false;
	}
	
	/**
	 * Fetches the next row and returns it as an object.
	 * 
	 * @param	string	$class_name - Name of the created class.
	 * @param	array	$ctor_class - Elements of this array are passed to the constructor.
	 * @return	object
	*/
	public function fetchObject($class_name = NULL, array $ctor_class = array())
	{
		if($this->executed == true)
		{
			if(!$this->resource)
			{
				return false;
			}
			elseif($this->isFetchAll == true)
			{
				throw new LikePDOStatement("There is no active statement");
				return false;
			}
		}
		else
		{
			throw new LikePDOStatement("There is no active statement");
			return false;
		}
		
		$fetch = $this->pdo->driver->fetch($this->resource, LikePDO::FETCH_OBJ);
		
		if($class_name)
		{
			if(!class_exists($class_name))
			{
				throw new LikePDOException("Class '".$class_name."' not found");
				return false;
			}
			else
			{
				if(!is_array($ctor_class))
				{
					throw new LikePDOException("Warning: LikePDOStatement::fetchObject() expects parameter 2 to be array, ".gettype($ctor_class)." given");
					return false;
				}
				
				$instance = new ReflectionClass($class_name);
				$foo = $instance->newInstanceArgs($ctor_class);
				
				if(count($fetch) > 0)
				{
					foreach($fetch as $key => $value)
					{
						$key = $this->realColumnAttribute($key);
						$foo->{$key} = $value;
					}
				}
				
				unset($fetch);
				return $foo;
			}
		}
		else
		{
			$return = new stdClass();
			
			if(count($fetch) > 0)
			{
				foreach($fetch as $key => $value)
				{
					$key = $this->realColumnAttribute($key);
					$return->{$key} = $value;
				}
			}
			
			unset($fetch);
			return $return;
		}		
	}
	
	/**
	 * Retrieve a statement attribute
	 * 
	 * @param	integer	$attribute - Gets an attribute of the statement.
	 * @return	mixed
	*/
	public function getAttribute($attribute)
	{
		
	}
	
	/**
	 * Returns metadata for a column in a result set
	 * 
	 * @param	integer	$column - The 0-indexed column in the result set.
	 * @return	array
	*/
	public function getColumnMeta($column)
	{
		$field = $this->pdo->driver->fetchField($this->resource, $column);
		$field = (array)$field;
		
		return $field;
	}
	
	/**
	 * Advances to the next rowset in a multi-rowset statement handle
	 * 
	 * @return	boolean
	*/
	public function nextRowset()
	{
		if(!is_resource($this->resource))
		{
			throw new LikePDOException("There is no active statement");
			return false;
		}
		else
		{
			return $this->pdo->driver->nextResult($this->resource);
		}
	}
	
	/**
	 * Returns the number of rows affected by the last SQL statement
	 * 
	 * @return	void
	*/
	public function rowCount()
	{
		return $this->rowsAffected;
	}
	
	/**
	 * Set a statement attribute
	 * 
	 * @param	integer	$attribute - Sets an attribute on the statement.
	 * @param	mixed	$value - Attribute value
	 * @return	void
	*/
	public function setAttribute($attribute, $value)
	{
		
	}
	
	/**
	 * Set the default fetch mode for this statement
	 * 
	 * @param	integer	$mode - The fetch mode must be one of the PDO::FETCH_* constants.
	 * @param	mixed	$fetch_arga - [int $colno] / [string $classname] / [object $object]
	 * @param	mixed	$fetch_argb - [array $ctorargs]
	 * @return	void
	*/
	public function setFetchMode($mode, $fetch_arga = NULL, $fetch_argb = NULL)
	{
		switch($mode)
		{
			case LikePDO::FETCH_ASSOC :
				$this->fetchMode = LikePDO::FETCH_ASSOC;
				$this->fetchModeDefinition = NULL;
				$this->fetchModeArguments = array();
			break;
			case LikePDO::FETCH_BOTH :
				$this->fetchMode = LikePDO::FETCH_BOTH;
				$this->fetchModeDefinition = NULL;
				$this->fetchModeArguments = array();
			break;
			case LikePDO::FETCH_BOUND :
				$this->fetchMode = LikePDO::FETCH_BOUND;
				$this->fetchModeDefinition = NULL;
				$this->fetchModeArguments = array();
			break;
			case LikePDO::FETCH_CLASS :
				if(!class_exists($fetch_arga))
				{
					throw new LikePDOException("No fetch class specified");
					return false;
				}
				
				if($fetch_argb)
				{
					if(!is_array($fetch_argb))
					{
						throw new LikePDOException("Warning: LikePDOStatement::setFetchMode() expects parameter 3 to be array on parameter 1 is FETCH_CLASS, ".gettype($fetch_argb)." given");
						return false;
					}
					
					$this->fetchModeArguments = $fetch_argb;
				}
				else
				{
					$this->fetchModeArguments = array();
				}	
				
				$this->fetchMode = LikePDO::FETCH_CLASS;
				$this->fetchModeDefinition = $fetch_arga;
			break;				
			break;
			case LikePDO::FETCH_INTO :
				if(!is_object($fetch_arga))
				{
					throw new LikePDOException("No fetch class specified");
					return false;
				}
				
				$this->fetchMode = LikePDO::FETCH_INTO;
				$this->fetchModeDefinition = $fetch_arga;
				$this->fetchModeArguments = array();
			break;
			case LikePDO::FETCH_LAZY :
				$this->fetchMode = LikePDO::FETCH_LAZY;
				$this->fetchModeDefinition = NULL;
				$this->fetchModeArguments = array();
			break;
			case LikePDO::FETCH_NAMED :
				$this->fetchMode = LikePDO::FETCH_NAMED;
				$this->fetchModeDefinition = NULL;
				$this->fetchModeArguments = array();
			break;
			case LikePDO::FETCH_NUM :
				$this->fetchMode = LikePDO::FETCH_NUM;
				$this->fetchModeDefinition = NULL;
				$this->fetchModeArguments = array();
			break;
			case LikePDO::FETCH_OBJ :
				$this->fetchMode = LikePDO::FETCH_OBJ;
				$this->fetchModeDefinition = NULL;
				$this->fetchModeArguments = array();
			break;
			default :
				throw new LikePDOException("Invalid fetch mode");
				return false;
			break;
		}
		
		return true;
	}
	
	/**
	 * Return the real column attribute by LikePDO::ATTR_*
	 * 
	 * @access	private
	 * @param	string	$column_name - The column name
	 * @return	string
	*/
	private function realColumnAttribute($column_name)
	{
		if($this->pdo->options[LikePDO::ATTR_CASE] == LikePDO::CASE_LOWER)
			return strtolower($column_name);
		elseif($this->pdo->options[LikePDO::ATTR_CASE] == LikePDO::CASE_UPPER)
			return strtoupper($column_name);
		else
			return $column_name;
	}
}