<?php

/**
 * Copyright (c) 2007, Gheorghe Constantin-Adrian. All rights reserved.
 * 
 * Code licensed under the BSD License:
 * http://adrian.lastdot.org/license.txt
 */

/**
 * Validates a ZIP code
 * 
 * @package Validate
 */
class RPC_Validator_Zip
{
	
	/**
	 * Returns value if it is a valid US ZIP, FALSE otherwise.
	 * 
	 * @param mixed $value
	 * 
	 * @return bool
	 */
	public function validate( $country, $value )
	{
		return (bool) preg_match( '/(^\d{5}$)|(^\d{5}-\d{4}$)/', $value );
	}
	
}

?>
