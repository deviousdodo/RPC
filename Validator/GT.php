<?php

/**
 * Copyright (c) 2007, Gheorghe Constantin-Adrian. All rights reserved.
 * 
 * Code licensed under the BSD License:
 * http://adrian.lastdot.org/license.txt
 */

class RPC_Validator_GT extends RPC_Validator
{
	
	protected $min;
	
	function __construct( $min, $errormessage = '' )
	{
		$this->min = $min;
		parent::__construct( $errormessage );
	}
	
	/**
	* Returns true if it is greater than $min, false otherwise.
	*
	* @param mixed $value
	* @return bool
	*/
	public function validate( $value )
	{
		return $value > $this->min;
	}
	
}

?>
