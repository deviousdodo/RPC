<?php

/**
 * Copyright (c) 2007, Gheorghe Constantin-Adrian. All rights reserved.
 * 
 * Code licensed under the BSD License:
 * http://adrian.lastdot.org/license.txt
 */

/**
 * Class representing a prepared query. It is not meant to be instantiated in
 * code, instead, an instance will be returned each time one executes
 * <code>RPC_Db_Adapter->prepare( $sql )</code>
 * 
 * @package Db
 */
class RPC_Db_Statement
{
	
	/**
	 * Database handle
	 * 
	 * @var RPC_Db_Adapter
	 */
	protected $db;
	
	/**
	 * Query to be executed
	 * 
	 * @var string
	 */
	protected $sql;
	
	/**
	 * Statement
	 * 
	 * @var PDOStatement
	 */
	protected $stmt;
	
	/**
	 * Prepares the query
	 * 
	 * @param string         $sql
	 * @param array          $options
	 * @param RPC_Db_Adapter $db
	 */
	public function __construct( $sql, $options = array(), RPC_Db_Adapter $db )
	{
		$this->db  = $db;
		$this->sql = $sql;
		
		$this->stmt = $this->db->getHandle()->prepare( $sql, $options );
		$this->stmt->setFetchMode( $this->db->getFetchMode() );
	}
	
	/**
	 * Sets how rows in the result should be returned
	 * 
	 * @param int $fetchmode
	 * 
	 * @return self
	 */
	public function setFetchMode( $fetchmode )
	{
		$this->stmt->setFetchMode( $fetchmode );
		return $this;
	}
	
	/**
	 * Executes a query and returns the result
	 * 
	 * @param array $params Parameters that should be replaced in the query
	 * 
	 * @return bool
	 */
	public function execute( $params = array() )
	{
		if( ! RPC_Signal::emit( array( 'RPC_Db', 'query_start' ), array( $this->sql, 'prepared' ) ) )
		{
			return null;
		}
		
		$res = $this->stmt->execute( $params );
		
		RPC_Signal::emit( array( 'RPC_Db', 'query_end' ), array( $this->sql, 'prepared' ) );
		
		if( $res )
		{
			if( stripos( trim( $this->sql ), 'select' ) === 0 )
			{
				$rows = $this->stmt->fetchAll();
				$this->stmt->closeCursor();
				
				return $rows;
			}
			
			return true;
		}
		
		return false;
	}
	
	/**
	 * Binds a parameter to the specified variable name
	 * 
	 * @param string|int $column
	 * @param mixed      $value
	 * @param int        $type
	 * @param int        $length
	 * @param mixed      $options
	 * 
	 * @return self
	 */
	public function bindParam( $param, &$value, $type = -1, $length = null, $options = null )
	{
		$this->stmt->bindParam( $param, $value, $type, $length, $options );
		
		return $this;
	}
	
	/**
	 * Binds a value to a parameter
	 * 
	 * @param string|int $column
	 * @param mixed      $value
	 * @param int        $type
	 * 
	 * @return self
	 */
	public function bindValue( $param, $value, $type = -1 )
	{
		$this->stmt->bindValue( $param, $value, $type );
		
		return $this;
	}
	
	/**
	 * Bind a column to a PHP variable
	 * 
	 * @param string|int $column
	 * @param mixed      $value
	 * @param int        $type
	 * 
	 * @return self
	 */
	public function bindColumn( $column, &$param, $type = null )
	{
		$this->stmt->bindColumn( $column, $param, $type );
		
		return $this;
	}
	
}

?>
