<?php

/**
 * Copyright (c) 2007, Gheorghe Constantin-Adrian. All rights reserved.
 * 
 * Code licensed under the BSD License:
 * http://adrian.lastdot.org/license.txt
 */

/**
 * Chains together multiple filters
 * 
 * @package Filter
 */
class RPC_Filter_Chain implements RPC_Filter
{
	
	/**
	 * Filters to be executed
	 * 
	 * @var array
	 */
	protected $chain = array();
	
	/**
	 * Adds a variable number of filters to the chain
	 */
	public function __construct()
	{
		if( func_num_args() )
		{
			$this->chain = func_get_args();
		}
	}
	
	/**
	 * Adds a filter rule to the chain
	 * 
	 * @param RPC_Filter $filter
	 * 
	 * @return RPC_Filter_Chain
	 */
	public function add( RPC_Filter $filter )
	{
		$this->chain[] = $filter;
		return $this;
	}
	
	/**
	 * Run all filters in chain
	 * 
	 * @param mixed $key
	 * 
	 * @return mixed
	 */
	public function filter( $value )
	{
		foreach( $this->chain as $filter )
		{
			$value = $filter->filter( $value );
		}
		
		return $value;
	}
	
}

?>
