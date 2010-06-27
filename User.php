<?php

/**
 * Copyright (c) 2007, Gheorghe Constantin-Adrian. All rights reserved.
 * 
 * Code licensed under the BSD License:
 * http://adrian.lastdot.org/license.txt
 */

/**
 * Basic skeleton class for a user inside an application
 * 
 * @package User
 */
abstract class RPC_User implements ArrayAccess
{
	
	/**
	 * Returns all the user's data
	 * 
	 * @return array
	 */
	public function getData()
	{
		return $this->data;
	}
	
	/**
	 * Sets the user data
	 * 
	 * @param array $data
	 */
	public function setData( $data )
	{
		$this->data = $data;
	}
	
	/**
	 * Returns the user's identifier
	 * 
	 * @return int
	 */
	abstract public function getId();
	
}

?>
