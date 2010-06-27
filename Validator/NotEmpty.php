<?php

/**
 * Copyright (c) 2007, Gheorghe Constantin-Adrian. All rights reserved.
 * 
 * Code licensed under the BSD License:
 * http://adrian.lastdot.org/license.txt
 */

class RPC_Validator_NotEmpty extends RPC_Validator
{
	
	public function validate( $value )
	{
		return ! empty( $value );
	}
	
}

?>
