<?php

/**
 * Copyright (c) 2007, Gheorghe Constantin-Adrian. All rights reserved.
 * 
 * Code licensed under the BSD License:
 * http://adrian.lastdot.org/license.txt
 */

class RPC_Validator_Numeric extends RPC_Validator
{
	
	/**
	 * Returns true if the given value is a numeric value
	 *
	 * @param mixed $value
	 * @return bool
	 */
	public function validate( $value )
	{
		return is_numeric( $value );
	}
	
}

?>
