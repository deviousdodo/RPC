<?php

/**
 * Copyright (c) 2007, Gheorghe Constantin-Adrian. All rights reserved.
 * 
 * Code licensed under the BSD License:
 * http://adrian.lastdot.org/license.txt
 */

/**
 * Removes characters from the input string based on a regular expression
 * 
 * @package Filter
 */
class RPC_Filter_Regex implements RPC_Filter
{
	
	/**
	 * Regex to replace the given value
	 * 
	 * @var string
	 */
	protected $pattern = null;
	
	/**
	 * Loads the regex which will remove the desired characters
	 * 
	 * @param string $pattern
	 */
	function __construct( $pattern )
	{
		$this->pattern = $pattern;
	}
	
	/**
	 * Returns value filtered by pattern.
	 * 
	 * @param mixed $value
	 * @param string $pattern
	 * 
	 * @return mixed
	 */
	public function filter( $value )
	{
		return preg_replace( $pattern, '', (string) $value );
	}
	
}

?>
