<?php
/**
 * LikePDO - Driver Interface
 * 
 * @name		PHP Like PDO
 * @author		Érick Carvalho <http://www.github.com/erickmcarvalho>
*/

namespace LikePDO\Interfaces;

use LikePDO;
use PDO;

interface DriverInterface
{
	/**
	 * Execute a query statement
	 * 
	 * @param	string	$statement - The query statement
	 * @return	boolean
	*/
	public function query($statement);
	
	/**
	 * Fetches the next row from a result set
	 * 
	 * @param	resource	$statement - Query statement
	 * @param	integer		$fetch_style - Fetch type
	 * @return	mixed
	*/
	public function fetch($statement, $fetch_style = LikePDO::FETCH_ASSOC);
	
	/**
	 * Get field information
	 * 
	 * @param	resource	$statement - The result resource that is being evaluated.
	 * @param	integer		$field_offset - The numerical field offset. If the field offset is not specified, the next field that was not yet retrieved by this function is retrieved. The field_offset starts at 0.
	 * @return	object
	*/
	public function fetchField($sstatement, $field_offset = -1);
	
	/**
	 * Move the internal result pointer to the next result
	 * 
	 * @param	resource	$statement - The result resource that is being evaluated.
	 * @return	boolean
	*/
	public function nextResult($statement);
	
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
	public function commitTransaction();
	
	/**
	 * Rolls back a transaction
	 * 
	 * @return	boolean
	*/
	public function rollBackTransaction();
	
	/**
	 * Returns the ID of the last inserted row or sequence value
	 * 
	 * @param	string	$name - Name of the sequence object from which the ID should be returned.
	 * @return	mixed
	*/
	public function getLastInsertId($name = NULL);
	
	/**
	 * Returns the number of records affected by the query
	 * 
	 * @return	integer
	*/
	public function getRowsAffected();
	
	/**
	 * Quotes a string for use in a query.
	 * 
	 * @param	string	$string - The string to be quoted.
	 * @param	integer	$parameter_type - Provides a data type hint for drivers that have alternate quoting styles. [default -> LikePDO::PARAM_STR]
	 * @return	mixed
	*/
	public function quoteString($string, $parameter_type = LikePDO::PARAM_STR);
	
	/**
	 * Fetch the SQLSTATE associated with the last operation on the database handle
	 * 
	 * @return	string	SQLSTATE, a five characters alphanumeric identifier defined in the ANSI SQL-92 standard.
	*/
	public function getSQLState();
	
	/**
	 * Fetch the error code associated with the last operation on the database handle
	 * 
	 * @return	integer	Error Code
	*/
	public function getErrorCode();
	
	/**
	 * Fetch extended error information associated with the last operation on the database handle
	 * 
	 * @return	array	Array of error information about the last operation performed by this database handle.
	*/
	public function getLastMessage();
}