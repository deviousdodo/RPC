<?php

/**
 * Copyright (c) 2007, Gheorghe Constantin-Adrian. All rights reserved.
 * 
 * Code licensed under the BSD License:
 * http://adrian.lastdot.org/license.txt
 */

/**
 * Removes all HTML tags from the input string
 * 
 * @package Filter
 */
class RPC_Filter_Tags implements RPC_Filter
{
	
	/**
	 * Returns value with all tags removed.
	 * 
	 * @param mixed $value
	 * 
	 * @return string
	 */
	public function filter( $value )
	{
		return strip_tags( (string) $value );
	}
	
}

?>
