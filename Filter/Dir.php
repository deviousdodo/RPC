<?php

/**
 * Copyright (c) 2007, Gheorghe Constantin-Adrian. All rights reserved.
 * 
 * Code licensed under the BSD License:
 * http://adrian.lastdot.org/license.txt
 */

/**
 * Takes out the directory component of the passed path
 * 
 * @package Filter
 */
class RPC_Filter_Dir implements RPC_Filter
{
	
	/**
	 * Returns dirname(value).
	 * 
	 * @param string $value
	 * 
	 * @return string
	 */
	public function filter( $value )
	{
		return dirname( (string) $value );
	}
	
}

?>
