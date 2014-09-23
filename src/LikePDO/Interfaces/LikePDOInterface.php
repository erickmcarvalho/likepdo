<?php
/**
 * LikePDO - Default Interface
 * Base/details: http://www.php.net/manual/en/pdo.constants.php
 * 
 * @name		PHP Like PDO
 * @author		Érick Carvalho <http://www.github.com/erickmcarvalho>
*/

namespace LikePDO\Interfaces;

if(!class_exists("\PDO"))
{
	interface PDO
	{
		const PARAM_BOOL				= 0xA005; // -- Represents a boolean data type.	
		const PARAM_NULL 				= 0xA000; // -- Represents the SQL NULL data type.
		const PARAM_INT					= 0xA001; // -- Represents the SQL INTEGER data type.
		const PARAM_STR					= 0xA002; // -- Represents the SQL CHAR, VARCHAR, or other string data type.
		const PARAM_LOB					= 0xA003; // -- Represents the SQL large object data type.
		const PARAM_STMT				= 0xA004; // -- Represents a recordset type. Not currently supported by any drivers.
		const PARAM_INPUT_OUTPUT		= 0xA0FF; // -- Specifies that the parameter is an INOUT parameter for a stored procedure.
		
		const FETCH_LAZY				= 0xB001; // -- Specifies that the fetch method shall return each row as an object with variable names that correspond to the column names returned in the result set.
		const FETCH_ASSOC				= 0xB002; // -- Specifies that the fetch method shall return each row as an array indexed by column name as returned in the corresponding result set.
		const FETCH_NAMED				= 0xB00B; // -- Specifies that the fetch method shall return each row as an array indexed by column name as returned in the corresponding result set.
		const FETCH_NUM					= 0xB003; // -- Specifies that the fetch method shall return each row as an array indexed by column number as returned in the corresponding result set, starting at column 0.
		const FETCH_BOTH				= 0xB004; // -- Specifies that the fetch method shall return each row as an array indexed by both column name and number as returned in the corresponding result set, starting at column 0.
		const FETCH_OBJ					= 0xB005; // -- Specifies that the fetch method shall return each row as an object with property names that correspond to the column names returned in the result set.
		const FETCH_BOUND				= 0xB006; // -- Specifies that the fetch method shall return TRUE and assign the values of the columns in the result set to the PHP variables to which they were bound with the LikePDOStatement::bindParam() or LikePDOStatement::bindColumn() methods.
		const FETCH_COLUMN				= 0xB007; // -- Specifies that the fetch method shall return only a single requested column from the next row in the result set.
		const FETCH_CLASS				= 0xB008; // -- Specifies that the fetch method shall return a new instance of the requested class, mapping the columns to named properties in the class.
		const FETCH_INTO				= 0xB009; // -- Specifies that the fetch method shall update an existing instance of the requested class, mapping the columns to named properties in the class.
		const FETCH_FUNC				= 0xB00A; // -- Allows completely customize the way data is treated on the fly (only valid inside LikePDOStatement::fetchAll()).
		const FETCH_GROUP				= 0xB0F0; // -- Group return by values. Usually combined with LikePDO::FETCH_COLUMN or LikePDO::FETCH_KEY_PAIR.
		const FETCH_UNIQUE				= 0xB0F1; // -- Fetch only the unique values.
		const FETCH_KEY_PAIR			= 0xB00C; // -- Fetch a two-column result into an array where the first column is a key and the second column is the value.
		const FETCH_CLASSTYPE			= 0xB0F2; // -- Determine the class name from the value of first column.
		const FETCH_SERIALIZE			= 0xB0F3; // -- As LikePDO::FETCH_INTO but object is provided as a serialized string.
		const FETCH_PROPS_LATE			= 0xB0F4; // -- Call the constructor before setting properties.
		
		const ATTR_AUTOCOMMIT			= 0xC000; // -- If this value is FALSE, LikePDO attempts to disable autocommit so that the connection begins a transaction.
		const ATTR_PREFETCH				= 0xC001; // -- Setting the prefetch size allows you to balance speed against memory usage for your application.
		const ATTR_TIMEOUT				= 0xC002; // -- Sets the timeout value in seconds for communications with the database.
		const ATTR_ERRMODE				= 0xC003; // -- See the Errors and error handling (http://www.php.net/manual/en/pdo.error-handling.php) section for more information about this attribute.
		const ATTR_SERVER_VERSION		= 0xC004; // -- This is a read only attribute; it will return information about the version of the database server to which PDO is connected.
		const ATTR_CLIENT_VERSION	 	= 0xC005; // -- This is a read only attribute; it will return information about the version of the client libraries that the PDO driver is using.
		const ATTR_SERVER_INFO			= 0xC006; // -- This is a read only attribute; it will return some meta information about the database server to which PDO is connected.
		const ATTR_CONNECTION_STATUS	= 0xC007; // -- [void]
		const ATTR_CASE					= 0xC008; // -- Force column names to a specific case specified by the LikePDO::CASE_* constants.
		const ATTR_CURSOR_NAME			= 0xC009; // -- Get or set the name to use for a cursor. Most useful when using scrollable cursors and positioned updates.
		const ATTR_CURSOR				= 0xC00A; // -- Selects the cursor type. LikePDO currently supports either LikePDO::CURSOR_FWDONLY and LikePDO::CURSOR_SCROLL. Stick with LikePDO::CURSOR_FWDONLY unless you know that you need a scrollable cursor.
		const ATTR_DRIVER_NAME			= 0xC010; // -- Returns the name of the driver.
		const ATTR_ORACLE_NULLS			= 0xC00B; // -- Convert empty strings to SQL NULL values on data fetches.
		const ATTR_PERSISTENT			= 0xC00C; // -- Request a persistent connection, rather than creating a new connection. See Connections and Connection management (http://www.php.net/manual/en/pdo.connections.php) for more information on this attribute.
		const ATTR_STATEMENT_CLASS		= 0xC00D; // -- [void]
		const ATTR_FETCH_CATALOG_NAMES	= 0xC00F; // -- Prepend the containing catalog name to each column name returned in the result set.
		const ATTR_FETCH_TABLE_NAMES	= 0xC00E; // -- Prepend the containing table name to each column name returned in the result set
		const ATTR_STRINGIFY_FETCHES	= 0xC011; // -- [void]
		const ATTR_MAX_COLUMN_LEN		= 0xC012; // -- [void]
		const ATTR_DEFAULT_FETCH_MODE	= 0xC013; // -- [void]
		const ATTR_EMULATE_PREPARES		= 0xC014; // -- [void]
		
		const ERRMODE_SILENT			= 0xD000; // -- Do not raise an error or exception if an error occurs.
		const ERRMODE_WARNING			= 0xD001; // -- Issue a PHP E_WARNING message if an error occurs.
		const ERRMODE_EXCEPTION			= 0xD002; // -- Throw a LikePDOException if an error occurs.
		
		const CASE_NATURAL				= 0xE000; // -- Leave column names as returned by the database driver.
		const CASE_LOWER 				= 0xE002; // -- Force column names to lower case.
		const CASE_UPPER				= 0xE001; // -- Force column names to upper case.
		
		const NULL_NATURAL				= 0xF000; // -- [void]
		const NULL_EMPTY_STRING			= 0xF001; // -- [void]
		const NULL_TO_STRING			= 0xF002; // -- [void]
		
		const FETCH_ORI_NEXT			= 0xA100; // -- Fetch the next row in the result set. Valid only for scrollable cursors.
		const FETCH_ORI_PRIOR			= 0xA101; // -- Fetch the previous row in the result set. Valid only for scrollable cursors.
		const FETCH_ORI_FIRST			= 0xA102; // -- Fetch the first row in the result set. Valid only for scrollable cursors.
		const FETCH_ORI_LAST			= 0xA103; // -- Fetch the last row in the result set. Valid only for scrollable cursors.
		const FETCH_ORI_ABS				= 0xA104; // -- Fetch the requested row by row number from the result set. Valid only for scrollable cursors.
		const FETCH_ORI_REL				= 0xA105; // -- Fetch the requested row by relative position from the current position of the cursor in the result set. Valid only for scrollable cursors.
		
		const CURSOR_FWDONLY			= 0xA200; // -- Create a LikePDOStatement object with a forward-only cursor. This is the default cursor choice, as it is the fastest and most common data access pattern in PHP.
		const CURSOR_SCROLL				= 0xA201; // -- Create a LikePDOStatement object with a scrollable cursor. Pass the LikePDO::FETCH_ORI_* constants to control the rows fetched from the result set.
	
		const ERR_NONE					= 0xA300; // -- Corresponds to SQLSTATE '00000', meaning that the SQL statement was successfully issued with no errors or warnings.
		
		const PARAM_EVT_ALLOC			= 0xA400; // -- Allocation event
		const PARAM_EVT_FREE			= 0xA401; // -- Deallocation event
		const PARAM_EVT_EXEC_PRE		= 0xA402; // -- Event triggered prior to execution of a prepared statement.
		const PARAM_EVT_EXEC_POST		= 0xA403; // -- Event triggered subsequent to execution of a prepared statement.
		const PARAM_EVT_FETCH_PRE		= 0xA404; // -- Event triggered prior to fetching a result from a resultset.
		const PARAM_EVT_FETCH_POST		= 0xA405; // -- Event triggered subsequent to fetching a result from a resultset.
		const PARAM_EVT_NORMALIZE		= 0xA406; // -- Event triggered during bound parameter registration allowing the driver to normalize the parameter name.
	}
	
	interface LikePDOConstantInterface extends PDO {}
}
else
{
	use PDO;
	
	interface LikePDOConstantInterface
	{
		const PARAM_BOOL				= PDO::PARAM_BOOL;
		const PARAM_NULL				= PDO::PARAM_NULL;
		const PARAM_INT					= PDO::PARAM_INT;
		const PARAM_STR					= PDO::PARAM_STR;
		const PARAM_LOB					= PDO::PARAM_LOB;
		const PARAM_STMT				= PDO::PARAM_STMT;
		const PARAM_INPUT_OUTPUT		= PDO::PARAM_INPUT_OUTPUT;
		
		const FETCH_LAZY				= PDO::FETCH_LAZY;
		const FETCH_ASSOC				= PDO::FETCH_ASSOC;
		const FETCH_NUM					= PDO::FETCH_NUM;
		const FETCH_BOTH				= PDO::FETCH_BOTH;
		const FETCH_OBJ					= PDO::FETCH_OBJ;
		const FETCH_BOUND				= PDO::FETCH_BOUND;
		const FETCH_COLUMN				= PDO::FETCH_COLUMN;
		const FETCH_CLASS				= PDO::FETCH_CLASS;
		const FETCH_INTO				= PDO::FETCH_INTO;
		const FETCH_FUNC				= PDO::FETCH_FUNC;
		const FETCH_GROUP				= PDO::FETCH_GROUP;
		const FETCH_UNIQUE				= PDO::FETCH_UNIQUE;
		const FETCH_KEY_PAIR			= PDO::FETCH_KEY_PAIR;
		const FETCH_CLASSTYPE			= PDO::FETCH_CLASSTYPE;
		const FETCH_SERIALIZE			= PDO::FETCH_SERIALIZE;
		const FETCH_PROPS_LATE			= PDO::FETCH_PROPS_LATE;
		
		const ATTR_AUTOCOMMIT			= PDO::ATTR_AUTOCOMMIT;
		const ATTR_PREFETCH				= PDO::ATTR_PREFETCH;
		const ATTR_TIMEOUT				= PDO::ATTR_TIMEOUT;
		const ATTR_ERRMODE				= PDO::ATTR_ERRMODE;
		const ATTR_SERVER_VERSION		= PDO::ATTR_CLIENT_VERSION;
		const ATTR_SERVER_INFO			= PDO::ATTR_SERVER_INFO;
		const ATTR_CONNECTION_STATUS	= PDO::ATTR_CONNECTION_STATUS;
		const ATTR_CASE					= PDO::ATTR_CASE;
		const ATTR_CURSOR_NAME			= PDO::ATTR_CURSOR_NAME;
		const ATTR_CURSOR				= PDO::ATTR_CURSOR;
		const ATTR_DRIVER_NAME			= PDO::ATTR_DRIVER_NAME;
		const ATTR_ORACLE_NULLS			= PDO::ATTR_ORACLE_NULLS;
		const ATTR_PERSISTENT			= PDO::ATTR_PERSISTENT;
		const ATTR_STATEMENT_CLASS		= PDO::ATTR_STATEMENT_CLASS;
		const ATTR_FETCH_CATALOG_NAMES	= PDO::ATTR_FETCH_CATALOG_NAMES;
		const ATTR_FETCH_TABLE_NAMES	= PDO::ATTR_FETCH_TABLE_NAMES;
		const ATTR_STRINGIFY_FETCHES	= PDO::ATTR_STRINGIFY_FETCHES;
		const ATTR_MAX_COLUMN_LEN		= PDO::ATTR_MAX_COLUMN_LEN;
		const ATTR_DEFAULT_FETCH_MODE	= PDO::ATTR_DEFAULT_FETCH_MODE;
		const ATTR_EMULATE_PREPARES		= PDO::ATTR_EMULATE_PREPARES;
		
		const ERRMODE_SILENT			= PDO::ERRMODE_SILENT;
		const ERRMODE_WARNING			= PDO::ERRMODE_WARNING;
		const ERRMODE_EXCEPTION			= PDO::ERRMODE_EXCEPTION;
		
		const CASE_NATURAL				= PDO::CASE_NATURAL;
		const CASE_LOWER				= PDO::CASE_LOWER;
		const CASE_UPPER				= PDO::CASE_UPPER;
		
		const NULL_NATURAL				= PDO::NULL_NATURAL;
		const NULL_EMPTY_STRING			= PDO::NULL_EMPTY_STRING;
		const NULL_TO_STRING			= PDO::NULL_TO_STRING;
		
		const FETCH_ORI_NEXT			= PDO::FETCH_ORI_NEXT;
		const FETCH_ORI_PRIOR			= PDO::FETCH_ORI_PRIOR;
		const FETCH_ORI_FIRST			= PDO::FETCH_ORI_FIRST;
		const FETCH_ORI_LAST			= PDO::FETCH_ORI_LAST;
		const FETCH_ORI_ABS				= PDO::FETCH_ORI_ABS;
		const FETCH_ORI_REL				= PDO::FETCH_ORI_REL;
		
		const CURSOR_FWDONLY			= PDO::CURSOR_FWDONLY;
		const CURSOR_SCROLL				= PDO::CURSOR_SCROLL;
		
		const ERR_NONE					= PDO::ERR_NONE;
		
		const PARAM_EVT_ALLOC			= PDO::PARAM_EVT_ALLOC;
		const PARAM_EVT_FREE			= PDO::PARAM_EVT_FREE;
		const PARAM_EVT_EXEC_PRE		= PDO::PARAM_EVT_EXEC_PRE;
		const PARAM_EVT_EXEC_POST		= PDO::PARAM_EVT_EXEC_POST;
		const PARAM_EVT_FETCH_PRE		= PDO::PARAM_EVT_FETCH_PRE;
		const PARAM_EVT_FETCH_POST		= PDO::PARAM_EVT_FETCH_POST;
		const PARAM_EVT_NORMALIZE		= PDO::PARAM_EVT_NORMALIZE;
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