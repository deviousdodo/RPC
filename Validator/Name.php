<?php

/**
 * Copyright (c) 2007, Gheorghe Constantin-Adrian. All rights reserved.
 * 
 * Code licensed under the BSD License:
 * http://adrian.lastdot.org/license.txt
 */

class RPC_Validator_Name extends RPC_Validator
{
	
	/**
	 * Returns value if it is a valid format for a person's name,
	 * false otherwise.
	 *
	 * @param mixed $value
	 * @return bool
	 */
	public function validate( $value )
	{
		return preg_match( RPC_Regex::NAME, $value  );
	}
	
}

?>
