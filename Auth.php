<?php

/**
 * Copyright (c) 2007, Gheorghe Constantin-Adrian. All rights reserved.
 * 
 * Code licensed under the BSD License:
 * http://adrian.lastdot.org/license.txt
 */

/**
 * A very basic skeleton for an authentification class
 * 
 * @package User
 */
interface RPC_Auth
{
	
	/**
	 * Should instantiate the user variable and return true if the
	 * authentification is successfull, false otherwise
	 * 
	 * @param string $username
	 * @param string $password
	 * 
	 * @return bool
	 */
	public function login( $username, $password );
	
	/**
	 * Checks to see if there is any user logged in
	 * 
	 * @return bool
	 */
	public function isLogged();
	
	/**
	 * Logs the current user out
	 * 
	 * @return bool
	 */
	public function logout();
	
	/**
	 * Returns the logged user
	 * 
	 * @return RPC_User
	 */
	public function getUser();
	
	/**
	 * Sets an object representing the logged in user
	 * 
	 * @param RPC_User $user
	 */
	public function setUser( RPC_User $user );
	
	/**
	 * Changes the info of the logged in user
	 * 
	 * @param RPC_User $user
	 */
	public function updateInfo( RPC_User $user );
	
}

?>
