<?php

/**
 * Copyright (c) 2007, Gheorghe Constantin-Adrian. All rights reserved.
 * 
 * Code licensed under the BSD License:
 * http://adrian.lastdot.org/license.txt
 */

/**
 * Skeleton for all filters
 * 
 * @package Filter
 */
interface RPC_Filter
{
	
	/**
	 * Transforms the input string and returns the result
	 * 
	 * @param string $input
	 */
	public function filter( $input );
	
}

?>
