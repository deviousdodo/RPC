<?php

/**
 * Copyright (c) 2007, Gheorghe Constantin-Adrian. All rights reserved.
 * 
 * Code licensed under the BSD License:
 * http://adrian.lastdot.org/license.txt
 */

/**
 * Identity map for all the rows loaded from one table
 * 
 * @package Db
 */
class RPC_Db_Table_RowMap
{
	
	/**
	 * Array containing the loaded rows with their primary keys as keys
	 * 
	 * @var array
	 */
	protected $map = array();
	
	/**
	 * Class constructor
	 */
	public function __construct()
	{
	}
	
	/**
	 * Adds a row to the map, only if the row doesn't yet exist
	 * 
	 * @param RPC_Db_Table_Row $row
	 */
	public function add( $row )
	{
		if( empty( $this->map[$row->getPk()] ) )
		{
			$this->map[$row->getPk()] = $row;
		}
	}
	
	/**
	 * Removes a row from the map
	 * 
	 * @param RPC_Db_Table_Row $row
	 */
	public function remove( $row )
	{
		unset( $this->map[$row->getPk()] );
	}
	
	/**
	 * Returns an instance of a row if there is one already stored, null
	 * otherwise
	 * 
	 * @param int $id
	 * 
	 * @return RPC_Db_Table_Row
	 */
	public function get( $id )
	{
		return isset( $this->map[$id] ) ? $this->map[$id] : null;
	}
	
}

?>
