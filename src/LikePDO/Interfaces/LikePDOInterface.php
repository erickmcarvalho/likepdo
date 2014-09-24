<?php
/**
 * LikePDO - Default Interface
 * Base/details: http://www.php.net/manual/en/pdo.constants.php
 * 
 * @name		PHP Like PDO
 * @author		Érick Carvalho <http://www.github.com/erickmcarvalho>
*/

namespace LikePDO\Interfaces;

if(!defined("\PDO::IS_LIKEPDO"))
{
	interface LikePDOConstantInterface extends PDO {}
}
else
{
	interface LikePDOConstantInterface
	{
		const PARAM_BOOL				= \PDO::PARAM_BOOL;
		const PARAM_NULL				= \PDO::PARAM_NULL;
		const PARAM_INT					= \PDO::PARAM_INT;
		const PARAM_STR					= \PDO::PARAM_STR;
		const PARAM_LOB					= \PDO::PARAM_LOB;
		const PARAM_STMT				= \PDO::PARAM_STMT;
		const PARAM_INPUT_OUTPUT		= \PDO::PARAM_INPUT_OUTPUT;
		
		const FETCH_LAZY				= \PDO::FETCH_LAZY;
		const FETCH_ASSOC				= \PDO::FETCH_ASSOC;
		const FETCH_NAMED				= \PDO::FETCH_NAMED;
		const FETCH_NUM					= \PDO::FETCH_NUM;
		const FETCH_BOTH				= \PDO::FETCH_BOTH;
		const FETCH_OBJ					= \PDO::FETCH_OBJ;
		const FETCH_BOUND				= \PDO::FETCH_BOUND;
		const FETCH_COLUMN				= \PDO::FETCH_COLUMN;
		const FETCH_CLASS				= \PDO::FETCH_CLASS;
		const FETCH_INTO				= \PDO::FETCH_INTO;
		const FETCH_FUNC				= \PDO::FETCH_FUNC;
		const FETCH_GROUP				= \PDO::FETCH_GROUP;
		const FETCH_UNIQUE				= \PDO::FETCH_UNIQUE;
		const FETCH_KEY_PAIR			= \PDO::FETCH_KEY_PAIR;
		const FETCH_CLASSTYPE			= \PDO::FETCH_CLASSTYPE;
		const FETCH_SERIALIZE			= \PDO::FETCH_SERIALIZE;
		const FETCH_PROPS_LATE			= \PDO::FETCH_PROPS_LATE;
		
		const ATTR_AUTOCOMMIT			= \PDO::ATTR_AUTOCOMMIT;
		const ATTR_PREFETCH				= \PDO::ATTR_PREFETCH;
		const ATTR_TIMEOUT				= \PDO::ATTR_TIMEOUT;
		const ATTR_ERRMODE				= \PDO::ATTR_ERRMODE;
		const ATTR_SERVER_VERSION		= \PDO::ATTR_CLIENT_VERSION;
		const ATTR_SERVER_INFO			= \PDO::ATTR_SERVER_INFO;
		const ATTR_CONNECTION_STATUS	= \PDO::ATTR_CONNECTION_STATUS;
		const ATTR_CASE					= \PDO::ATTR_CASE;
		const ATTR_CURSOR_NAME			= \PDO::ATTR_CURSOR_NAME;
		const ATTR_CURSOR				= \PDO::ATTR_CURSOR;
		const ATTR_DRIVER_NAME			= \PDO::ATTR_DRIVER_NAME;
		const ATTR_ORACLE_NULLS			= \PDO::ATTR_ORACLE_NULLS;
		const ATTR_PERSISTENT			= \PDO::ATTR_PERSISTENT;
		const ATTR_STATEMENT_CLASS		= \PDO::ATTR_STATEMENT_CLASS;
		const ATTR_FETCH_CATALOG_NAMES	= \PDO::ATTR_FETCH_CATALOG_NAMES;
		const ATTR_FETCH_TABLE_NAMES	= \PDO::ATTR_FETCH_TABLE_NAMES;
		const ATTR_STRINGIFY_FETCHES	= \PDO::ATTR_STRINGIFY_FETCHES;
		const ATTR_MAX_COLUMN_LEN		= \PDO::ATTR_MAX_COLUMN_LEN;
		const ATTR_DEFAULT_FETCH_MODE	= \PDO::ATTR_DEFAULT_FETCH_MODE;
		const ATTR_EMULATE_PREPARES		= \PDO::ATTR_EMULATE_PREPARES;
		
		const ERRMODE_SILENT			= \PDO::ERRMODE_SILENT;
		const ERRMODE_WARNING			= \PDO::ERRMODE_WARNING;
		const ERRMODE_EXCEPTION			= \PDO::ERRMODE_EXCEPTION;
		
		const CASE_NATURAL				= \PDO::CASE_NATURAL;
		const CASE_LOWER				= \PDO::CASE_LOWER;
		const CASE_UPPER				= \PDO::CASE_UPPER;
		
		const NULL_NATURAL				= \PDO::NULL_NATURAL;
		const NULL_EMPTY_STRING			= \PDO::NULL_EMPTY_STRING;
		const NULL_TO_STRING			= \PDO::NULL_TO_STRING;
		
		const FETCH_ORI_NEXT			= \PDO::FETCH_ORI_NEXT;
		const FETCH_ORI_PRIOR			= \PDO::FETCH_ORI_PRIOR;
		const FETCH_ORI_FIRST			= \PDO::FETCH_ORI_FIRST;
		const FETCH_ORI_LAST			= \PDO::FETCH_ORI_LAST;
		const FETCH_ORI_ABS				= \PDO::FETCH_ORI_ABS;
		const FETCH_ORI_REL				= \PDO::FETCH_ORI_REL;
		
		const CURSOR_FWDONLY			= \PDO::CURSOR_FWDONLY;
		const CURSOR_SCROLL				= \PDO::CURSOR_SCROLL;
		
		const ERR_NONE					= \PDO::ERR_NONE;
		
		const PARAM_EVT_ALLOC			= \PDO::PARAM_EVT_ALLOC;
		const PARAM_EVT_FREE			= \PDO::PARAM_EVT_FREE;
		const PARAM_EVT_EXEC_PRE		= \PDO::PARAM_EVT_EXEC_PRE;
		const PARAM_EVT_EXEC_POST		= \PDO::PARAM_EVT_EXEC_POST;
		const PARAM_EVT_FETCH_PRE		= \PDO::PARAM_EVT_FETCH_PRE;
		const PARAM_EVT_FETCH_POST		= \PDO::PARAM_EVT_FETCH_POST;
		const PARAM_EVT_NORMALIZE		= \PDO::PARAM_EVT_NORMALIZE;
	}
}

interface LikePDOInterface extends LikePDOConstantInterface
{
	/**
	 * Initiates a transaction
	 * 
	 * @return	boolean
	*/
	public function beginTransaction();
	
	/**
	 * Commits a transaction
	 * 
	 * @return	void
	*/
	public function commit();
	
	/**
	 * Fetch the SQLSTATE associated with the last operation on the database handle
	 * 
	 * @return	string	SQLSTATE, a five characters alphanumeric identifier defined in the ANSI SQL-92 standard.
	*/
	public function errorCode();
	
	/**
	 * Fetch extended error information associated with the last operation on the database handle
	 * 
	 * @return	array	Array of error information about the last operation performed by this database handle.
	*/
	public function errorInfo();
	
	/**
	 * Execute an SQL statement and return the number of affected rows
	 * 
	 * @param	string	$statement - The SQL statement to prepare and execute.
	 * @return	integer	Rows that were modified or deleted by the SQL statement you issued.
	*/
	public function exec($statement);
	
	/**
	 * Retrieve a database connection attribute
	 * 
	 * @param	integer	$attribute - One of the PDO::ATTR_* constants.
	 * @return	mixed	A successful call returns the value of the requested PDO attribute. An unsuccessful call returns null.
	*/
	public function getAttribute($attribute);
	
	/**
	 * Return an array of available LikePDO drivers
	 * 
	 * @return	array
	*/
	public function getAvailableDrivers();
	
	/**
	 * Checks if inside a transaction
	 * 
	 * @return	boolean
	*/
	public function inTransaction();
	
	/**
	 * Returns the ID of the last inserted row or sequence value
	 * 
	 * @param	string	$name - Name of the sequence object from which the ID should be returned. [default -> NULL]
	 * @return	mixed
	*/
	public function lastInsertId($name = NULL);
	
	/**
	 * Prepares a statement for execution and returns a statement object
	 * 
	 * @param	string	$statement - This must be a valid SQL statement for the target database server.
	 * @param	array	$driver_options - This array holds one or more key=>value pairs to set attribute values for the PDOStatement object that this method returns. [default -> array()]
	 * @return	object	LikePDOStatement
	*/
	public function prepare($statement, array $driver_options = array());
	
	/**
	 * Executes an SQL statement, returning a result set as a LikePDOStatement object
	 * 
	 * @param	string	$statement - The SQL statement to prepare and execute.
	 * @param	integer	$fetch_type - LikePDO::FETCH_COLUMN / LikePDO::FETCH_COLUMN / LikePDO::FETCH_INTO [default -> NULL]
	 * @param	mixed	$fetch_arga - [int $colno] / [string $classname] / [object $object]
	 * @param	mixed	$fetch_argb - [array $ctorargs]
	*/
	public function query($statement, $fetch_type = NULL, $fetch_arga = NULL, $fetch_argb = NULL);
	
	/**
	 * Quotes a string for use in a query.
	 * 
	 * @param	string	$string - The string to be quoted.
	 * @param	integer	$parameter_type - Provides a data type hint for drivers that have alternate quoting styles. [default -> LikePDO::PARAM_STR]
	 * @return	mixed
	*/
	public function quote($string, $parameter_type = self::PARAM_STR);
	
	/**
	 * Rolls back a transaction
	 * 
	 * @return	boolean
	*/
	public function rollBack();
	
	/**
	 * Set an attribute
	 * 
	 * @param	integer	$attribute - The attribute name
	 * @param	mixed	$value - The attribute value
	 * @return	boolean
	*/
	public function setAttribute($attribute, $value);
}