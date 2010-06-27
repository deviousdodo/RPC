<?php

/**
 * Copyright (c) 2007, Gheorghe Constantin-Adrian. All rights reserved.
 * 
 * Code licensed under the BSD License:
 * http://adrian.lastdot.org/license.txt
 */

class RPC_Validator_Float extends RPC_Validator
{
	
	/**
	* Returns value if it is a valid float value, FALSE otherwise.
	*
	* @param mixed $value
	* @return bool
	*/
	public function validate( $value )
	{
		return is_float( $value );
	}
	
}

?>
