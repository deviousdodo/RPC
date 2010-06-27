<?php

/**
 * Copyright (c) 2007, Gheorghe Constantin-Adrian. All rights reserved.
 * 
 * Code licensed under the BSD License:
 * http://adrian.lastdot.org/license.txt
 */

/**
 * MySQL driver implementation as a base for all model classes, mapping a table
 * 
 * @package Db
 */
abstract class RPC_Db_Table_Adapter_MySQL extends RPC_Db_Table_Adapter
{
	
	/**
	 * @todo Use a cache
	 */
	public function loadFields()
	{
		/**
		 * I store the fields in a global variable for performance reasons: if
		 * the table is instantiated more than once, then the query and field
		 * building is done only once
		 */
		if( ! empty( $GLOBALS['_RPC_']['models'][$this->getName()]['fields'] ) )
		{
			$this->fields      = $GLOBALS['_RPC_']['models'][$this->getName()]['fields'];
			$this->cleanfields = $GLOBALS['_RPC_']['models'][$this->getName()]['cleanfields'];
		}
		else
		{
			$res = $this->getDb()->query( 'describe `' . $this->getName() . '`' );
			
			$table_prefix = str_replace( $this->getDb()->getPrefix(), '', $this->getName() ) . '_';
			foreach( $res as $row )
			{
				$this->fields[] = $row['field'];
				
				$clean = str_replace( '_', '', str_replace( $table_prefix, '', $row['field'] ) );
				$this->cleanfields[$clean] = $row['field'];
			}
			
			$res = null;
			
			$GLOBALS['_RPC_']['models'][$this->getName()]['fields']      = $this->fields;
			$GLOBALS['_RPC_']['models'][$this->getName()]['cleanfields'] = $this->cleanfields;
		}
	}
	
	public function getAll()
	{
		$sql = 'select * from `' . $this->getName() . '`';
		return $this->getDb()->query( $sql );
	}
	
	public function getBy( $field, $value )
	{
		$fields = $this->getFields();
		$cleanfields = $this->getCleanFields();
		
		$field = strtolower( $field );
		
		$realfield = in_array( $field, $fields ) ? $field : @$cleanfields[$field];
		
		if( ! $realfield )
		{
			throw new InvalidArgumentException( 'Field `' . $field . '` does not exist' );
		}
		
		$sql = 'select * from `' . $this->getName() . '` where '
		     . '`' . $realfield . '`= ?';
		return $this->getDb()->prepare( $sql )->execute( array( $value ) );
	}
	
	public function loadBy( $field, $value )
	{
		$fields = $this->getFields();
		$cleanfields = $this->getCleanFields();
		
		$field = strtolower( $field );
		
		$field = in_array( $field, $fields ) ? $field : @$cleanfields[$field];
		
		if( ! $field )
		{
			throw new InvalidArgumentException( 'Field `' . $field . '` does not exist' );
		}
		
		$sql = 'select * from `' . $this->getName() . '` where `'
		     . $field . '`=? limit 1';
		$res = $this->getDb()->prepare( $sql )->execute( array( $value ) );
		
		if( count( $res ) )
		{
			$row = $res[0];
			
			/**
			 * Applying any defined conversions on fields
			 */
			foreach( $cleanfields as $cf => $f )
			{
				$method = 'convert_' . $cf;
				
				if( method_exists( $this, $method ) )
				{
					$row[$f] = $this->$method( $row[$f] );
				}
			}
			
			return new $this->rowclass( $this, $row );
		}
		
		return null;
	}
	
	protected function insertRow( RPC_Db_Table_Row $row )
	{
		$columns = array();
		$values    = array();
		foreach( $row->getChangedFields() as $column => $yes )
		{
			if( $column == $this->getPkField() )
			{
				continue;
			}
			
			$columns[] = '`' . $column . '`';
			
			$method = 'revert_' . array_search( $column, $this->cleanfields );
			if( method_exists( $this, $method ) )
			{
				$values[] = $this->$method( $row[$column] );
			}
			else
			{
				$values[]  = $row[$column];
			}
		}
		
		$sql  = 'insert into `' . $this->getName() . '` ';
		$sql .= '(' . implode( ',', $columns ) . ') values ';
		$sql .= '(' . implode( ',', array_fill( 0, count( $columns ), '?' ) ) . ')';
		
		return $this->getDb()->prepare( $sql )->execute( $values );
	}
	
	public function updateRow( RPC_Db_Table_Row $row )
	{
		$columns = array();
		$values  = array();
		$parts   = array();
		$sql = 'update `' . $this->getName() . '` set ';
		foreach( $row->getChangedFields() as $column => $yes )
		{
			if( $column != $this->getPkField() )
			{
				$parts[]  = '`' . $column . '`=?';
				
				$method = 'revert_' . array_search( $column, $this->cleanfields );
				if( method_exists( $this, $method ) )
				{
					$values[] = $this->$method( $row[$column] );
				}
				else
				{
					$values[]  = $row[$column];
				}
			}
		}
		
		$sql .= implode( ',', $parts );
		$sql .= ' where `' . $this->getPkField() . '`=?';
		$values[] = $row->getPk();
		
		return $this->getDb()->prepare( $sql )->execute( $values );
	}
	
	public function deleteBy( $field, $value )
	{
		$fields = $this->getFields();
		$cleanfields = $this->getCleanFields();
		
		$field = strtolower( $field );
		
		$field = in_array( $field, $fields ) ? $field : $cleanfields[$field];
		$sql   = 'delete from `' . $this->getName() . '` where `' . $field . '`=?';
		
		return $this->getDb()->prepare( $sql )->execute( array( $value ) );
	}
	
	public function deleteAll()
	{
		return $this->getDb()->execute( 'delete from `' . $this->getName() . '`' );
	}
	
	public function truncate()
	{
		return $this->getDb()->execute( 'truncate table `' . $this->getName() . '`' );
	}
	
	public function countRows()
	{
		$res = $this->getDb()->query( 'select count(*) as nr from `' . $this->getName() . '`' );
		
		return (int) $res[0]['nr'];
	}
	
	public function exists( $value, $column )
	{
		if( ! in_array( $column, $this->getFields() ) )
		{
			throw new Exception( 'Column "' . $column . '" does not exist in table ' . $this->getName() );
		}
		
		$res = $this->getDb()->prepare( 'select count(*) as nr from `' . $this->getName() . '` where `' . $column . '`=? limit 1' )->execute( array( $value ) );
		
		return (int) $res[0]['nr'];
	}
	
	public function unique( $column, RPC_Db_Table_Row $row )
	{
		$values[0] = $row[$column];
		
		$sql = 'select count(*) as nr from `' . $this->getName() . '` where '
		     . '`' . $column . '`=?';
		if( $row->getPk() )
		{
			$sql .= ' and ' . $this->getPkField() . '!=?';
			$values[] = $row->getPk();
		}
		
		$res = $this->getDb()->prepare( $sql )->execute( $values );
		$row = $res[0];
		
		return ! (int) $row['nr'];
	}
	
	/**
	 * @todo Implement
	 */
	public function lock()
	{
		throw new Exception( 'Not implemented' );
	}
	
	/**
	 * @todo Implement
	 */
	public function unlock()
	{
		throw new Exception( 'Not implemented' );
	}
	
}

?>
