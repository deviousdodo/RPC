<?php

/**
 * Copyright (c) 2007, Gheorghe Constantin-Adrian. All rights reserved.
 * 
 * Code licensed under the BSD License:
 * http://adrian.lastdot.org/license.txt
 */

class RPC_Validator_Digits extends RPC_Validator
{
	
	/**
	 * Returns true if every character is a digit, true otherwise.
	 * This is just like isInt(), except there is no upper limit.
	 *
	 * @param mixed $value
	 * @return bool
	 */
	public function validate( $value )
	{
		return ctype_digit( $value );
	}
	
}

?>
