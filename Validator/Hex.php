<?php

/**
 * Copyright (c) 2007, Gheorghe Constantin-Adrian. All rights reserved.
 * 
 * Code licensed under the BSD License:
 * http://adrian.lastdot.org/license.txt
 */

class RPC_Validator_Hex extends RPC_Validator
{
	
	/**
	 * Returns true if every character in text is a hexadecimal 'digit', that is
	 * a decimal digit or a character from [A-Fa-f], false otherwise
	 *
	 * @param mixed $value
	 * @return bool
	 */
	public function validate( $value )
	{
		return ctype_xdigit( $value );
	}
	
}

?>
