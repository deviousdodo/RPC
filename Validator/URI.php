<?php

/**
 * Copyright (c) 2007, Gheorghe Constantin-Adrian. All rights reserved.
 * 
 * Code licensed under the BSD License:
 * http://adrian.lastdot.org/license.txt
 */

/**
 * Checks if the given string is an URI
 * 
 * @package Validate
 */
class RPC_Validator_URI extends RPC_Validator
{
	
	/**
	 * Checks if the given string is an URI
	 * 
	 * @param string $value
	 * 
	 * @return bool
	 */
	public function validate( $value )
	{
		return preg_match( RPC_Regex::URI , $value );
	}
	
}

?>
