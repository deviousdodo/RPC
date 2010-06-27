<?php

/**
 * Copyright (c) 2007, Gheorghe Constantin-Adrian. All rights reserved.
 * 
 * Code licensed under the BSD License:
 * http://adrian.lastdot.org/license.txt
 */

class RPC_Validator_Length extends RPC_Validator
{
	
	protected $min;
	
	protected $max;
	
	function __construct( $min = 0, $max = 0, $errormessage = '' )
	{
		$this->max = $max;
		$this->min = $min;
		
		parent::__construct( $errormessage );
	}
	
	/**
	 * Returns true if its length is greater than $min and less than
	 * $max, false otherwise. If one of the given values is 0 it is not taken
	 * into consideration anymore.
	 * 
	 * @param mixed $value
	 * @return bool
	 */
	public function validate( $value )
	{
		if( $this->min == 0 &&
		    $this->max == 0 )
		{
			throw new Exception( 'Illegal arguments' );
		}
		
		$length = strlen( $value );
		
		return ( $this->min <= $length ) && ( $length <= $this->max );
	}
	
}

?>
