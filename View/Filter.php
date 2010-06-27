<?php

/**
 * Copyright (c) 2007, Gheorghe Constantin-Adrian. All rights reserved.
 * 
 * Code licensed under the BSD License:
 * http://adrian.lastdot.org/license.txt
 */

/**
 * Interface which needs to be implemented by all view filters.
 * 
 * Filters will be applied before running it through the PHP interpreter, and
 * should add PHP code, so that the result(ing logic) can be cached.
 * 
 * @package View
 */
interface RPC_View_Filter
{
	
	/**
	 * Should replace HTML markup and return the new source code
	 * 
	 * @param string $source
	 */
	public function filter( $source );
	
}

?>
