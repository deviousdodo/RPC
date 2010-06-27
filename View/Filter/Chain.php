<?php

/**
 * Copyright (c) 2007, Gheorghe Constantin-Adrian. All rights reserved.
 * 
 * Code licensed under the BSD License:
 * http://adrian.lastdot.org/license.txt
 */

/**
 * Chain of filters which allows for multiple filters to be added so they can be
 * ran sequentially
 * 
 * @package View
 */
class RPC_View_Filter_Chain implements RPC_View_Filter
{
	
	/**
	 * Array containing filters
	 * 
	 * @var array
	 */
	protected $_view_filters = array();
	
	/**
	 * Class constructor which allows a variable number of filters to be added
	 */
	public function __construct()
	{
		if( func_num_args() )
		{
			$this->_view_filters = func_get_args();
		}
	}
	
	/**
	 * Adds a new filter to the queue
	 * 
	 * @param RPC_View_Filter $filter
	 * 
	 * @return self
	 */
	public function addFilter( RPC_View_Filter $filter )
	{
		$this->_view_filters[] = $filter;
		
		return $this;
	}
	
	/**
	 * Removes a filter from the queue
	 * 
	 * @param RPC_View_Filter $filter
	 * 
	 * @return RPC_View
	 */
	public function removeFilter( RPC_View_Filter $filter )
	{
		$key = array_search( $filter, $this->_rpc_filters );
		if( $key !== false )
		{
			unset( $this->_rpc_filters[$key] );
		}
		return $this;
	}
	
	/**
	 * Returns an array of previously loaded filters
	 * 
	 * @return array
	 */
	public function getFilters()
	{
		return $this->_view_filters;
	}
	
	/**
	 * Filters the source code through all registered filters
	 * 
	 * @param string $source
	 * 
	 * @return string
	 */
	public function filter( $source )
	{
		foreach( $this->_view_filters as $filter )
		{
			$source = $filter->filter( $source );
		}
		
		return $source;
	}
	
}

?>
