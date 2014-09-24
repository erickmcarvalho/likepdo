<?php
/**
 * LikePDO - The Row Class
 * 
 * @name		PHP Like PDO
 * @author		Érick Carvalho <http://www.github.com/erickmcarvalho>
*/

namespace LikePDO\Instances;

use stdClass;

class LikePDORow extends stdClass
{
	/**
	 * Query string
	 * 
	 * @access	public
	 * @var		string
	*/
	public $queryString	= NULL;
}