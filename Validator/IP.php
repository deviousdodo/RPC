<?php

/**
 * Copyright (c) 2007, Gheorghe Constantin-Adrian. All rights reserved.
 * 
 * Code licensed under the BSD License:
 * http://adrian.lastdot.org/license.txt
 */

class RPC_Validator_IP extends RPC_Validator
{
	
	/**
	 * Returns true if it is a valid IPV4 format, false otherwise.
	 *
	 * @param mixed $value
	 * @return bool
	 */
	public function validate( $value )
	{
		return (bool)ip2long( $value );
	}
	
}

?>
