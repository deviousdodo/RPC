<?php

/**
 * Copyright (c) 2007, Gheorghe Constantin-Adrian. All rights reserved.
 * 
 * Code licensed under the BSD License:
 * http://adrian.lastdot.org/license.txt
 */

/**
 * Removes all non-numeric characters from a string
 * 
 * @package Filter
 */
class RPC_Filter_Alpha implements RPC_Filter
{
	
	/**
	 * Returns only the alphabetic characters in value.
	 * 
	 * @param string $value
	 * 
	 * @return string
	 */
	public function filter( $value )
	{
		return preg_replace( '/[^[:alpha:]]/', '', $value );
	}
	
}

?>
