<?php

/**
 * Copyright (c) 2007, Gheorghe Constantin-Adrian. All rights reserved.
 * 
 * Code licensed under the BSD License:
 * http://adrian.lastdot.org/license.txt
 */

/**
 * Interprets the request and fetches the parameters, the command object and
 * specific action
 * 
 * @package Controller
 */
interface RPC_Controller_Router
{
	
	/**
	 * Returns the params array from the request
	 * 
	 * @return array
	 */
	public function getParams();
	
	/**
	 * Execute the routing process
	 */
	public function route();
	
}

?>
