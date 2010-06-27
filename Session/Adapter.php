<?php

/**
 * Copyright (c) 2007, Gheorghe Constantin-Adrian. All rights reserved.
 * 
 * Code licensed under the BSD License:
 * http://adrian.lastdot.org/license.txt
 */

interface RPC_Session_Adapter
{
	
	/**
	 * Opens any necessary resource and sets the save_path and
	 * the session name (if necessary)
	 *
	 * @param string $save_path
	 * @param string $session_name
	 * @return bool
	 */
	public function open( $save_path, $session_name );
	
	/**
	 * Performs closing actions
	 * 
	 * @return bool
	 */
	public function close();
	
	/**
	 * Reads the session's content
	 * 
	 * @return string
	 */
	public function read( $id );
	
	/**
	 * Writes the session content
	 *
	 * @return bool
	 */
	public function write( $id, $data );
	
	/**
	 * Removes the session along with all it's data
	 * 
	 * @return bool
	 */
	public function destroy( $id );
	
	/**
	 * Deletes the expired sessions
	 *
	 * @param int $max_lifetime Maximum life time of a session
	 * @return bool
	 */
	public function gc( $max_lifetime );
	
	/**
	 * Counts the number of open sessions
	 * 
	 * @return int
	 */
	public function count();
	
	/**
	 * Loads modified configuration options
	 */
	public function setOptions( $options = array() );
	
}

?>
