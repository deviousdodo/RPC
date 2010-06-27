<?php

/**
 * Copyright (c) 2007, Gheorghe Constantin-Adrian. All rights reserved.
 * 
 * Code licensed under the BSD License:
 * http://adrian.lastdot.org/license.txt
 */

/**
 * Removes all non-alfanumeric characters
 * 
 * @package Filter
 */
class RPC_Filter_Alnum implements RPC_Filter
{
	
	/**
	 * Returns only the alphabetic characters and digits in value.
	 * 
	 * @param string $value
	 * 
	 * @return string
	 */
	public function filter( $value )
	{
		return preg_replace( '/[^[:alnum:]]/', '', $value );
	}
	
}

?>
