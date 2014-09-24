<?php
/**
 * LikePDO - Default Interface
 * Base/details: http://www.php.net/manual/en/pdo.constants.php
 * 
 * @name		PHP Like PDO
 * @author		Érick Carvalho <http://www.github.com/erickmcarvalho>
*/

namespace LikePDO\Interfaces;

interface LikePDOInterface
{
	const PARAM_BOOL				= \PDO::PARAM_BOOL;					// -- Represents a boolean data type.	
	const PARAM_NULL				= \PDO::PARAM_NULL;					// -- Represents the SQL NULL data type.
	const PARAM_INT					= \PDO::PARAM_INT;					// -- Represents the SQL INTEGER data type.
	const PARAM_STR					= \PDO::PARAM_STR;					// -- Represents the SQL CHAR, VARCHAR, or other string data type.
	const PARAM_LOB					= \PDO::PARAM_LOB;					// -- Represents the SQL large object data type.
	const PARAM_STMT				= \PDO::PARAM_STMT;					// -- Represents a recordset type. Not currently supported by any drivers.
	const PARAM_INPUT_OUTPUT		= \PDO::PARAM_INPUT_OUTPUT;			// -- Specifies that the parameter is an INOUT parameter for a stored procedure.
	
	const FETCH_LAZY				= \PDO::FETCH_LAZY;					// -- Specifies that the fetch method shall return each row as an object with variable names that correspond to the column names returned in the result set.
	const FETCH_ASSOC				= \PDO::FETCH_ASSOC;				// -- Specifies that the fetch method shall return each row as an array indexed by column name as returned in the corresponding result set.
	const FETCH_NAMED				= \PDO::FETCH_NAMED;				// -- Specifies that the fetch method shall return each row as an array indexed by column name as returned in the corresponding result set.
	const FETCH_NUM					= \PDO::FETCH_NUM;					// -- Specifies that the fetch method shall return each row as an array indexed by column number as returned in the corresponding result set, starting at column 0.
	const FETCH_BOTH				= \PDO::FETCH_BOTH;					// -- Specifies that the fetch method shall return each row as an array indexed by both column name and number as returned in the corresponding result set, starting at column 0.
	const FETCH_OBJ					= \PDO::FETCH_OBJ;					// -- Specifies that the fetch method shall return each row as an object with property names that correspond to the column names returned in the result set.
	const FETCH_BOUND				= \PDO::FETCH_BOUND;				// -- Specifies that the fetch method shall return TRUE and assign the values of the columns in the result set to the PHP variables to which they were bound with the LikePDOStatement::bindParam() or LikePDOStatement::bindColumn() methods.
	const FETCH_COLUMN				= \PDO::FETCH_COLUMN;				// -- Specifies that the fetch method shall return only a single requested column from the next row in the result set.
	const FETCH_CLASS				= \PDO::FETCH_CLASS;				// -- Specifies that the fetch method shall return a new instance of the requested class, mapping the columns to named properties in the class.
	const FETCH_INTO				= \PDO::FETCH_INTO;					// -- Specifies that the fetch method shall update an existing instance of the requested class, mapping the columns to named properties in the class.
	const FETCH_FUNC				= \PDO::FETCH_FUNC;					// -- Allows completely customize the way data is treated on the fly (only valid inside LikePDOStatement::fetchAll()).
	const FETCH_GROUP				= \PDO::FETCH_GROUP;				// -- Group return by values. Usually combined with LikePDO::FETCH_COLUMN or LikePDO::FETCH_KEY_PAIR.
	const FETCH_UNIQUE				= \PDO::FETCH_UNIQUE;				// -- Fetch only the unique values.
	const FETCH_KEY_PAIR			= \PDO::FETCH_KEY_PAIR;				// -- Fetch a two-column result into an array where the first column is a key and the second column is the value.
	const FETCH_CLASSTYPE			= \PDO::FETCH_CLASSTYPE;			// -- Determine the class name from the value of first column.
	const FETCH_SERIALIZE			= \PDO::FETCH_SERIALIZE;			// -- As LikePDO::FETCH_INTO but object is provided as a serialized string.
	const FETCH_PROPS_LATE			= \PDO::FETCH_PROPS_LATE;			// -- Call the constructor before setting properties.
	
	const ATTR_AUTOCOMMIT			= \PDO::ATTR_AUTOCOMMIT;			// -- If this value is FALSE, LikePDO attempts to disable autocommit so that the connection begins a transaction.
	const ATTR_PREFETCH				= \PDO::ATTR_PREFETCH;				// -- Setting the prefetch size allows you to balance speed against memory usage for your application.
	const ATTR_TIMEOUT				= \PDO::ATTR_TIMEOUT;				// -- Sets the timeout value in seconds for communications with the database.
	const ATTR_ERRMODE				= \PDO::ATTR_ERRMODE;				// -- See the Errors and error handling (http://www.php.net/manual/en/pdo.error-handling.php) section for more information about this attribute.
	const ATTR_SERVER_VERSION		= \PDO::ATTR_SERVER_VERSION;		// -- This is a read only attribute; it will return information about the version of the database server to which PDO is connected.
	const ATTR_CLIENT_VERSION		= \PDO::ATTR_CLIENT_VERSION;		// -- This is a read only attribute; it will return information about the version of the client libraries that the PDO driver is using.
	const ATTR_SERVER_INFO			= \PDO::ATTR_SERVER_INFO;			// -- This is a read only attribute; it will return some meta information about the database server to which PDO is connected.
	const ATTR_CONNECTION_STATUS	= \PDO::ATTR_CONNECTION_STATUS;		// -- [void]
	const ATTR_CASE					= \PDO::ATTR_CASE;					// -- Force column names to a specific case specified by the LikePDO::CASE_* constants.
	const ATTR_CURSOR_NAME			= \PDO::ATTR_CURSOR_NAME;			// -- Get or set the name to use for a cursor. Most useful when using scrollable cursors and positioned updates.
	const ATTR_CURSOR				= \PDO::ATTR_CURSOR;				// -- Selects the cursor type. LikePDO currently supports either LikePDO::CURSOR_FWDONLY and LikePDO::CURSOR_SCROLL. Stick with LikePDO::CURSOR_FWDONLY unless you know that you need a scrollable cursor.
	const ATTR_DRIVER_NAME			= \PDO::ATTR_DRIVER_NAME;			// -- Returns the name of the driver.
	const ATTR_ORACLE_NULLS			= \PDO::ATTR_ORACLE_NULLS;			// -- Convert empty strings to SQL NULL values on data fetches.
	const ATTR_PERSISTENT			= \PDO::ATTR_PERSISTENT;			// -- Request a persistent connection, rather than creating a new connection. See Connections and Connection management (http://www.php.net/manual/en/pdo.connections.php) for more information on this attribute.
	const ATTR_STATEMENT_CLASS		= \PDO::ATTR_STATEMENT_CLASS;		// -- [void]
	const ATTR_FETCH_CATALOG_NAMES	= \PDO::ATTR_FETCH_CATALOG_NAMES;	// -- Prepend the containing catalog name to each column name returned in the result set.
	const ATTR_FETCH_TABLE_NAMES	= \PDO::ATTR_FETCH_TABLE_NAMES;		// -- Prepend the containing table name to each column name returned in the result set
	const ATTR_STRINGIFY_FETCHES	= \PDO::ATTR_STRINGIFY_FETCHES;		// -- [void]
	const ATTR_MAX_COLUMN_LEN		= \PDO::ATTR_MAX_COLUMN_LEN;		// -- [void]
	const ATTR_DEFAULT_FETCH_MODE	= \PDO::ATTR_DEFAULT_FETCH_MODE;	// -- [void]
	const ATTR_EMULATE_PREPARES		= \PDO::ATTR_EMULATE_PREPARES;		// -- [void]
	
	const ERRMODE_SILENT			= \PDO::ERRMODE_SILENT;				// -- Do not raise an error or exception if an error occurs.
	const ERRMODE_WARNING			= \PDO::ERRMODE_WARNING;			// -- Issue a PHP E_WARNING message if an error occurs.
	const ERRMODE_EXCEPTION			= \PDO::ERRMODE_EXCEPTION;			// -- Throw a LikePDOException if an error occurs.
	
	const CASE_NATURAL				= \PDO::CASE_NATURAL;				// -- Leave column names as returned by the database driver.
	const CASE_LOWER				= \PDO::CASE_LOWER;					// -- Force column names to lower case.
	const CASE_UPPER				= \PDO::CASE_UPPER;					// -- Force column names to upper case.
	
	const NULL_NATURAL				= \PDO::NULL_NATURAL;				// -- [void]
	const NULL_EMPTY_STRING			= \PDO::NULL_EMPTY_STRING;			// -- [void]
	const NULL_TO_STRING			= \PDO::NULL_TO_STRING;				// -- [void]
	
	const FETCH_ORI_NEXT			= \PDO::FETCH_ORI_NEXT;				// -- Fetch the next row in the result set. Valid only for scrollable cursors.
	const FETCH_ORI_PRIOR			= \PDO::FETCH_ORI_PRIOR;			// -- Fetch the previous row in the result set. Valid only for scrollable cursors.
	const FETCH_ORI_FIRST			= \PDO::FETCH_ORI_FIRST;			// -- Fetch the first row in the result set. Valid only for scrollable cursors.
	const FETCH_ORI_LAST			= \PDO::FETCH_ORI_LAST;				// -- Fetch the last row in the result set. Valid only for scrollable cursors.
	const FETCH_ORI_ABS				= \PDO::FETCH_ORI_ABS;				// -- Fetch the requested row by row number from the result set. Valid only for scrollable cursors.
	const FETCH_ORI_REL				= \PDO::FETCH_ORI_REL;				// -- Fetch the requested row by relative position from the current position of the cursor in the result set. Valid only for scrollable cursors.
	
	const CURSOR_FWDONLY			= \PDO::CURSOR_FWDONLY;				// -- Create a LikePDOStatement object with a forward-only cursor. This is the default cursor choice, as it is the fastest and most common data access pattern in PHP.
	const CURSOR_SCROLL				= \PDO::CURSOR_SCROLL;				// -- Create a LikePDOStatement object with a scrollable cursor. Pass the LikePDO::FETCH_ORI_* constants to control the rows fetched from the result set.
	
	const ERR_NONE					= \PDO::ERR_NONE;					// -- Corresponds to SQLSTATE '00000', meaning that the SQL statement was successfully issued with no errors or warnings.
	
	const PARAM_EVT_ALLOC			= \PDO::PARAM_EVT_ALLOC;			// -- Allocation event
	const PARAM_EVT_FREE			= \PDO::PARAM_EVT_FREE;				// -- Deallocation event
	const PARAM_EVT_EXEC_PRE		= \PDO::PARAM_EVT_EXEC_PRE;			// -- Event triggered prior to execution of a prepared statement.
	const PARAM_EVT_EXEC_POST		= \PDO::PARAM_EVT_EXEC_POST;		// -- Event triggered subsequent to execution of a prepared statement.
	const PARAM_EVT_FETCH_PRE		= \PDO::PARAM_EVT_FETCH_PRE;		// -- Event triggered prior to fetching a result from a resultset.
	const PARAM_EVT_FETCH_POST		= \PDO::PARAM_EVT_FETCH_POST;		// -- Event triggered subsequent to fetching a result from a resultset.
	const PARAM_EVT_NORMALIZE		= \PDO::PARAM_EVT_NORMALIZE;		// -- Event triggered during bound parameter registration allowing the driver to normalize the parameter name.

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