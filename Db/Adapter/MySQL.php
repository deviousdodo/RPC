<?php

/**
 * Copyright (c) 2007, Gheorghe Constantin-Adrian. All rights reserved.
 * 
 * Code licensed under the BSD License:
 * http://adrian.lastdot.org/license.txt
 */

/**
 * MySQLi Adapter implementation
 * 
 * @package Db
 */
class RPC_Db_Adapter_MySQL extends RPC_Db_Adapter
{
	
	/**
	 * Database hostname
	 * 
	 * @var string
	 */
	protected $_rpc_hostname = 'localhost';
	
	/**
	 * Database name
	 * 
	 * @var string
	 */
	protected $_rpc_database = '';
	
	/**
	 * Server socket location
	 * 
	 * @var string
	 */
	protected $_rpc_socket = '';
	
	/**
	 * Server's listening port
	 * 
	 * @var int
	 */
	protected $_rpc_port = null;
	
	/**
	 * Class constructor
	 * 
	 * @param string $hostname
	 * @param string $database
	 * @param string $socket
	 * @param int $port
	 */
	public function __construct( $hostname = 'localhost', $database = null, $socket = null, $port = 3306 )
	{
		$this->_rpc_hostname = $hostname;
		$this->_rpc_database = $database;
		$this->_rpc_socket   = $socket;
		$this->_rpc_port     = $port;
	}
	
	/**
	 * Attempts to connect to the database, throwing an exception if it fails
	 * 
	 * @param string $username
	 * @param string $password
	 * @param int    $options
	 * 
	 * @return RPC_Db_Adapter_MySQL
	 */
	public function connect( $username, $password, $options = null )
	{
		if( $this->_rpc_socket )
		{
			$dns = 'mysql:unix_socket=' . $this->_rpc_socket . ';dname=' . $this->_rpc_database;
		}
		else
		{
			$dsn = 'mysql:host=' . $this->_rpc_hostname . ';dbname=' . $this->_rpc_database;
		}
		
		$this->setHandle( new PDO( $dsn, $username, $password ) );
		$this->getHandle()->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
		$this->getHandle()->setAttribute( PDO::ATTR_CASE, PDO::CASE_LOWER );
		$this->getHandle()->setAttribute( PDO::ATTR_AUTOCOMMIT, true );
		
		return $this;
	}
	
	/**
	 * Overiding the default implementation as it seems to have a bug, at least
	 * with MySQL
	 * 
	 * @return int
	 */
	public function getLastId()
	{
		$sql = 'select last_insert_id() as n';
		$res = $this->query( $sql );
		
		return $res[0]['n'];
	}
	
	/**
	 * Returns the number of rows found by the last query containing the
	 * SQL_CALC_FOUND_ROWS operator
	 * 
	 * @return int
	 */
	public function getFoundRows()
	{
		$res = $this->getHandle()->query( 'select found_rows() as f' );
		$row = $res->fetch();
		return $row['f'];
	}
	
	/**
	 * Set the default charset for the connection
	 * 
	 * @param string $charset
	 * 
	 * @return bool
	 */
	public function setCharset( $charset = 'utf8' )
	{
		return $this->getHandle()->exec( 'set charset ' . $charset );
	}
	
	/**
	 * Prepares a query and returns a new statement
	 * 
	 * @param string $sql
	 * @param array  $options
	 * 
	 * @return RPC_Db_Statement
	 */
	public function prepare( $sql, $options = array() )
	{
		return new RPC_Db_Statement( $sql, $options, $this );
	}
	
}

?>
