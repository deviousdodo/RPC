<?php

/**
 * Copyright (c) 2007, Gheorghe Constantin-Adrian. All rights reserved.
 * 
 * Code licensed under the BSD License:
 * http://adrian.lastdot.org/license.txt
 */

class RPC_Validator_Alnum extends RPC_Validator
{
	
	/**
	 * Validates if every characther is either a letter or a number
	 *
	 * @param string $value
	 * @return bool
	 */
	public function validate( $value )
	{
		return ctype_alnum( $value );
	}
	
}

?>
