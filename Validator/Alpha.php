<?php

/**
 * Copyright (c) 2007, Gheorghe Constantin-Adrian. All rights reserved.
 * 
 * Code licensed under the BSD License:
 * http://adrian.lastdot.org/license.txt
 */

class RPC_Validator_Alpha extends RPC_Validator
{
	
	/**
	 * Validates if every character of $value is a letter
	 *
	 * @param string $value
	 * @return bool
	 */
	public function validate( $value )
	{
		return ctype_alpha( $value );
	}
	
}

?>
