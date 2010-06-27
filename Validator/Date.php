<?php

/**
 * Copyright (c) 2007, Gheorghe Constantin-Adrian. All rights reserved.
 * 
 * Code licensed under the BSD License:
 * http://adrian.lastdot.org/license.txt
 */

class RPC_Validator_Date extends RPC_Validator
{
	
	/**
	 * Date format
	 *
	 * @var string
	 */
	protected $format = 'Y-m-d';
	
	public function __construct( $format = 'Y-m-d', $errormessage = '' )
	{
		$this->format = $format;
		$this->setError( $errormessage );
	}
	
	/**
	 * Returns true if it is a valid date, false otherwise.
	 *
	 * @param mixed $value
	 * @return bool
	 */
	public function validate( $value )
	{
		return RPC_Date::validDate( $value, $this->format );
	}
	
}

?>
