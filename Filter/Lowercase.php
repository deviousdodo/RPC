<?php

/**
 * Copyright (c) 2007, Gheorghe Constantin-Adrian. All rights reserved.
 * 
 * Code licensed under the BSD License:
 * http://adrian.lastdot.org/license.txt
 */

/**
 * Makes the input string upppercase
 * 
 * @package Filter
 */
class RPC_Filter_Lowercase implements RPC_Filter
{
	
	/**
	 * Returns lowercase value.
	 * 
	 * @param mixed $value
	 * 
	 * @return string
	 */
	public function filter( $value )
	{
		return strtolower( (string) $value );
	}
	
}

?>
