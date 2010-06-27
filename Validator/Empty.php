<?php

/**
 * Copyright (c) 2007, Gheorghe Constantin-Adrian. All rights reserved.
 * 
 * Code licensed under the BSD License:
 * http://adrian.lastdot.org/license.txt
 */

class RPC_Validator_Empty extends RPC_Validator
{
	
	/**
	 * Checks if the given value is empty
	 *
	 * @param mixed $value
	 * @return bool
	 */
	public function validate( $value )
	{
		return empty( $value );
	}
	
}

?>
