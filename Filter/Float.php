<?php

/**
 * Copyright (c) 2007, Gheorghe Constantin-Adrian. All rights reserved.
 * 
 * Code licensed under the BSD License:
 * http://adrian.lastdot.org/license.txt
 */

/**
 * Coerces the given variables to a float
 * 
 * @package Filter
 */
class RPC_Filter_Float implements RPC_Filter
{
	
	/**
	 * Returns (float) value.
	 * 
	 * @param mixed $value
	 * 
	 * @return float
	 */
	public function filter( $value )
	{
		return (float) $value;
	}
	
}

?>
