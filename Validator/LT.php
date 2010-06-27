<?php

/**
 * Copyright (c) 2007, Gheorghe Constantin-Adrian. All rights reserved.
 * 
 * Code licensed under the BSD License:
 * http://adrian.lastdot.org/license.txt
 */

class RPC_Validator_LT extends RPC_Validator
{
	
	protected $max;
	
	function __construct( $max, $errormessage = '' )
	{
		$this->max = $max;
		parent::__construct( $errormessage );
	}
	
	/**
	 * Returns true if it is less than $max, false otherwise.
	 *
	 * @param mixed $value
	 * @return bool
	 */
	public function validate( $value )
	{
		return $value < $this->max;
	}
	
}

?>
