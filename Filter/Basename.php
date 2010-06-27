<?php

/**
 * Copyright (c) 2007, Gheorghe Constantin-Adrian. All rights reserved.
 * 
 * Code licensed under the BSD License:
 * http://adrian.lastdot.org/license.txt
 */

/**
 * Returns the basename component of the given path
 * 
 * @package Filter
 */
class RPC_Filter_Basename implements RPC_Filter
{
	
	/**
	 * Returns basename(value).
	 * 
	 * @param string $value
	 * 
	 * @return string
	 */
	public function filter( $value )
	{
		return basename( $value );
	}
    
}

?>
