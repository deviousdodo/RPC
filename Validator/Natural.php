<?php

/**
 * Copyright (c) 2007, Gheorghe Constantin-Adrian. All rights reserved.
 * 
 * Code licensed under the BSD License:
 * http://adrian.lastdot.org/license.txt
 */

class RPC_Validator_Natural extends RPC_Validator
{
	
	/**
	 * Returns true if the given value is an integer
	 *
	 * @param mixed $value
	 * @return bool
	 */
	public function validate( $value )
	{
		if( ( ! is_numeric( $value ) ) ||
		    ( (int) $value != $value ) ||
		    ( $value < 0 ) )
		{
			return false;
		}
		
		return true;
	}
	
}

?>
