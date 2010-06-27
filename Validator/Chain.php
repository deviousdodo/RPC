<?php

/**
 * Copyright (c) 2007, Gheorghe Constantin-Adrian. All rights reserved.
 * 
 * Code licensed under the BSD License:
 * http://adrian.lastdot.org/license.txt
 */

class RPC_Validator_Chain extends RPC_Validator
{
	
	protected $chain = array();
	
	/**
	 * Dynamic constructor, in that you can add all the rules through it
	 */
	public function __construct()
	{
		if( func_num_args() )
		{
			$this->chain = func_get_args();
		}
	}
	
	/**
	 * Adds a new rule to the chain
	 *
	 * @param RPC_Validator_Interface $validator
	 * @return RPC_Validator_Chain
	 */
	public function add( RPC_Validator $validator )
	{
		$this->chain[] = $validator;
		return $this;
	}
	
	/**
	 * The chain will break on the first error occured. The error message will
	 * be that of the validator that failed
	 *
	 * @param mixed $value
	 * @return bool
	 */
	public function validate( $value )
	{
		foreach( $this->chain as $validator )
		{
			if( ! $validator->validate( $value ) )
			{
				$this->setError( $validator->getError() );
				return false;
			}
		}
		
		return true;
	}
	
}

?>
