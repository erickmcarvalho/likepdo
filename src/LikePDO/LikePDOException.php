<?php
/**
 * LikePDO Exception
 * 
 * @name		PHP Like PDO
 * @author		Érick Carvalho <http://www.github.com/erickmcarvalho>
*/

namespace LikePDO;

class LikePDOException extends Exception
{
	/**
	 * Get Message
	 * 
	 * @return	string
	*/
	public function getMessage()
	{
		return "[LikePDO] ".$this->getMessage();
	}
}