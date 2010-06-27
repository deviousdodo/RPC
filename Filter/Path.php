<?php

/**
 * Copyright (c) 2007, Gheorghe Constantin-Adrian. All rights reserved.
 * 
 * Code licensed under the BSD License:
 * http://adrian.lastdot.org/license.txt
 */

/**
 * Returns the path of a filesystem identifier
 * 
 * @package Filter
 */
class RPC_Filter_Path implements RPC_Filter
{
	
	/**
	 * Returns the path part of the given string
	 * 
	 * @param mixed $value
	 * 
	 * @return mixed
	 */
	public function filter( $value )
	{
		return realpath( (string) $value );
	}
	
}

?>
