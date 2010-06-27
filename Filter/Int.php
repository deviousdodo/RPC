<?php

/**
 * Copyright (c) 2007, Gheorghe Constantin-Adrian. All rights reserved.
 * 
 * Code licensed under the BSD License:
 * http://adrian.lastdot.org/license.txt
 */

/**
 * Coerces the given variable to an integer
 * 
 * @package Filter
 */
class RPC_Filter_Int implements RPC_Filter
{
	
	/**
	 * Returns (int) value
	 * 
	 * @param mixed $value
	 * 
	 * @return int
	 */
	public function filter( $value )
	{
		return (int) $value;
	}
	
}

?>
