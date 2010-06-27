<?php

/**
 * Copyright (c) 2007, Gheorghe Constantin-Adrian. All rights reserved.
 * 
 * Code licensed under the BSD License:
 * http://adrian.lastdot.org/license.txt
 */

class RPC_Session_Adapter_MySQLi implements RPC_Session_Adapter
{
	
	protected $database = null;
	
	protected $table_name = '';
	
	public function __construct( RPC_Database_Adapter $database, $table_name )
	{
		$this->database   = $database;
		$this->table_name = $table_name;
	}
	
	public function setOptions( $options = array() )
	{
		/**
		 * Some configurations here, eventually setting the column names or
		 * something la that.
		 */
	}
	
	public function open( $save_path, $session_name )
	{
		return true;
	}
	
	public function close()
	{
		return true;
	}
	
	public function read( $id )
	{
		$sql = 'select * from `' . $this->table_name . '` where id="' . $id . '"';
		$res = $this->database->executeRaw( $sql );
		$row = mysqli_fetch_assoc( $res );
		return $row['data'];
	}
	
	public function write( $id, $data )
	{
		$id     = $this->database->escape( $id );
		$data   = $this->database->escape( $data );
		
		$sql = "replace into `" . $this->table_name . "` values ('$id', "
		     . "'$data', now())";
		return $this->database->executeRaw( $sql );
	}
	
	public function destroy( $id )
	{
		$sql = 'delete from `' . $this->table_name . '` where id="' . $id . '"';
		return $this->database->executeRaw( $sql );
	}
	
	public function gc( $max_lifetime )
	{
		$sql = 'delete from `' . $this->table_name . '` where '
		     . 'last_access < date_sub( now(), interval ' . $max_lifetime . ' second )';
		return $this->database->executeRaw( $sql );
	}
	
	public function count()
	{
		$sql = 'select count(*) as no from `' . $this->table_name . '`';
		$res = $this->database->executeRaw( $sql );
		$row = mysqli_fetch_assoc( $res );
		return $row['no'];
	}
	
	public function __destruct()
	{
		session_write_close();
	}
	
}

?>
