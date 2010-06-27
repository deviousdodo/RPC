<?php

/**
 * Copyright (c) 2007, Gheorghe Constantin-Adrian. All rights reserved.
 * 
 * Code licensed under the BSD License:
 * http://adrian.lastdot.org/license.txt
 */

/**
 * Removes all non-digit characters from the string
 * 
 * @package Filter
 */
class RPC_Filter_Digits implements RPC_Filter
{
	
	/**
	 * Returns only the digits in value. This differs from getInt()
	 * 
	 * @param string $value
	 * 
	 * @return int
	 */
	public function filter( $value )
	{
		return preg_replace( '/[^\d]/', '', (string) $value );
	}
	
}

?>
