<?php

/**
 * Copyright (c) 2007, Gheorghe Constantin-Adrian. All rights reserved.
 * 
 * Code licensed under the BSD License:
 * http://adrian.lastdot.org/license.txt
 */

/**
 * Trims a string to a maximum length
 * 
 * @package Filter
 */
class RPC_Filter_Length implements RPC_Filter
{
	
	/**
	 * Maximum length
	 * 
	 * @var int
	 */
	protected $length;
	
	/**
	 * Sets the maximum length of the string
	 * 
	 * @param int $length
	 */
	function __construct( $length )
	{
		$this->length = $length;
	}
	
	/**
	 * Returns first $length characters of value.
	 * 
	 * @param mixed $value
	 * @param int $length
	 * 
	 * @return mixed
	 */
	public function filter( $value )
	{
		return substr( (string) $value, 0, $length );
	}
	
}

?>
