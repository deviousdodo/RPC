<?php

/**
 * Copyright (c) 2007, Gheorghe Constantin-Adrian. All rights reserved.
 * 
 * Code licensed under the BSD License:
 * http://adrian.lastdot.org/license.txt
 */

/**
 * Generic Row class that can be used with any database adapter
 * 
 * @package Db
 */
class RPC_Db_Table_Row implements ArrayAccess
{
	
	/**
	 * Stores a reference to the parent table
	 * 
	 * @var RPC_Db_Table_Adapter
	 */
	protected $table = null;
	
	/**
	 * Array with errors found when trying to save
	 * 
	 * @var array
	 */
	protected $errors = array();
	
	/**
	 * Array containing the original field values
	 * 
	 * @var array
	 */
	protected $clean = null;
	
	/**
	 * Array of fields whose values have been changed
	 * 
	 * @var array
	 */
	protected $changedfields = array();
	
	/**
	 * Array containing the actual row
	 * 
	 * @var array
	 */
	protected $row = array();
	
	/**
	 * Changes to true if any of the record's values are changed
	 * 
	 * @var bool
	 */
	protected $dirty = false;

	/**
	 * Array containing other fields than the table's attributes
	 *
	 * @var array
	 */
	protected $extrafields = array();
	
	/**
	 * Class constructor
	 * 
	 * @param RPC_Db_Table_Adapter $table
	 * @param object               $row
	 */
	public function __construct( RPC_Db_Table_Adapter $table, $row = array() )
	{
		$this->setTable( $table );
		
		$this->clean = $row;
		$this->row   = $row;
	}
	
	/**
	 * Convenience method for returning database object
	 * 
	 * @return RPC_Db_Adapter
	 */
	public function getDb()
	{
		return $this->getTable()->getDb();
	}
	
	/**
	 * Sets the table instance to which the row belongs
	 * 
	 * @param RPC_Db_Table_Adapter $table
	 */
	protected function setTable( RPC_Db_Table_Adapter $table )
	{
		$this->table = $table;
	}
	
	/**
	 * Returns the table instance where the row belongs
	 * 
	 * @return RPC_Db_Table_Adapter
	 */
	public function getTable()
	{
		return $this->table;
	}
	
	/**
	 * Just a shortcut method for calling the table method with the same name
	 * 
	 * @return array
	 */
	public function getFields()
	{
		return $this->getTable()->getFields();
	}
	
	/**
	 * Returns an array whose keys are the names of the fields whose
	 * values have been changed
	 * 
	 * @return array
	 */
	public function getChangedFields()
	{
		return $this->changedfields;
	}
	
	/**
	 * Sets the "dirty" status for the row, indicating if it has changed or not
	 * 
	 * @param bool $dirty
	 * 
	 * @return RPC_Db_Table_Row
	 */
	protected function setDirty( $dirty )
	{
		$this->dirty = $dirty;
		
		return $this;
	}
	
	/**
	 * Returns true if any change has occured on the record
	 * 
	 * @return bool
	 */
	public function isDirty()
	{
		return $this->dirty;
	}
	
	/**
	 * Returns an array with the initial (ie when it was retrieved from the
	 * table) values in the row
	 * 
	 * @return array
	 */
	public function getCleanArray()
	{
		return $this->clean;
	}
	
	/**
	 * Reverts the row to it's original state when it was retrieved from the
	 * database or since the last save
	 * 
	 * @return RPC_Db_Table_Row
	 */
	public function revert()
	{
		$this->row = $this->getCleanArray();
		
		return $this;
	}
	
	/**
	 * In case any change occurs on the record, then it is marked as dirty
	 * 
	 * @param string|int $index
	 * @param mixed      $newval
	 * 
	 * @implements ArrayAccess
	 */
	public function offsetSet( $index, $newval )
	{
		if( $this->offsetExists( $index ) )
		{
			$this->changedfields[$index] = 1;
		}
		else
		{
			$this->extrafields[$index] = $newval;
		}
		
		/**
		 * The primary key cannot be set using $row[field] syntax, there is a
		 * special method
		 * 
		 * @see self::setPk()
		 */
		if( $index == $this->getTable()->getPkField() )
		{
			throw new Exception( 'The primary key can only be changed using the setPk method' );
		}
		
		/**
		 * Only if the record already exists in the database we should mark it
		 * as dirty when a change occurs on one field
		 */
		if( $this->getPk() &&
		    $this->offsetGet( $index ) != $newval )
		{
			$this->setDirty( true );
		}
		
		$this->row[$index] = $newval;
	}
	
	/**
	 * N/A
	 * 
	 * @param string $index
	 * 
	 * @implements ArrayAccess
	 */
	public function offsetUnset( $index )
	{
		throw new Exception( 'You cannot remove a field from the row' );
	}
	
	/**
	 * Checks to see if a certain field exists
	 * 
	 * @param string $index Field name
	 * 
	 * @implements ArrayAccess
	 */
	public function offsetExists( $index )
	{
		return in_array( $index, $this->getFields() );
	}
	
	/**
	 * Returns the value for the required field
	 * 
	 * @param string $index Field name
	 * 
	 * @return mixed
	 * 
	 * @implements ArrayAccess
	 */
	public function offsetGet( $index )
	{
		if( ! $this->offsetExists( $index ) )
		{
			return $this->extrafields[$index];
		}
		
		return $this->row[$index];
	}
	
	/**
	 * Sets a value for the row's primary key. This should not be needed in the
	 * application, but is provided as it is used internally, after an row is
	 * inserted
	 * 
	 * @param mixed $pk
	 * 
	 * @return RPC_Db_Table_Row
	 */
	public function setPk( $pk )
	{
		if( empty( $pk ) )
		{
			throw new Exception( 'Primary key cannot be empty' );
		}
		
		$this->row[$this->getTable()->getPkField()] = $pk;
		
		return $this;
	}
	
	/**
	 * Returns the row's primary key value
	 * 
	 * @return int
	 */
	public function getPk()
	{
		return $this->offsetGet( $this->getTable()->getPkField() );
	}
	
	/**
	 * Returns the number of errors on this row
	 * 
	 * @return int
	 */
	public function hasErrors()
	{
		return count( $this->errors );
	}
	
	/**
	 * Sets an array of errors for multiple fields in the row
	 * 
	 * @param array $errors
	 * 
	 * @return RPC_Db_Table_Row
	 */
	public function setErrors( $errors )
	{
		$this->errors = $errors;
		
		return $this;
	}
	
	/**
	 * Return the error messages
	 * 
	 * @return array
	 */
	public function getErrors()
	{
		return $this->errors;
	}
	
	/**
	 * Sets an error for a specific table field
	 * 
	 * @param string $field
	 * @param string $error
	 * 
	 * @return RPC_Db_Table_Row
	 */
	public function setError( $field, $error = '' )
	{
		$this->errors[$field] = $error;
		
		return $this;
	}
	
	/**
	 * Returns the error for a specific table field
	 * 
	 * @return string
	 */
	public function getError( $field )
	{
		return isset( $this->errors[$field] ) ? $this->errors[$field] : null;
	}
	
	/**
	 * Fill the values on the row with values from an array. This is a better
	 * choice as opposed to calling <code>$row->validate( $values )</code>
	 * because one may need to add more data to the row before validating
	 * 
	 * @param array $values
	 * 
	 * @return RPC_Db_Table_Row
	 */
	public function populate( $values )
	{
		foreach( $this->getFields() as $field )
		{
			if( isset( $values[$field] ) &&
			    ( $field != $this->getTable()->getPkField() ) )
			{
				$this->offsetSet( $field, $values[$field] );
			}
		}
		
		return $this;
	}
	
	/**
	 * Checks to see if the values on a row are valid
	 * 
	 * @return bool
	 */
	public function validate()
	{
		return $this->getTable()->validate( $this );
	}
	
	/**
	 * Inserts or updates an array into a table, based on the primary key: if
	 * the primary key is empty it will insert, otherwise update
	 */
	public function save()
	{
		$pk = $this->getPk();
		empty( $pk ) ? $this->getTable()->insert( $this ) : $this->getTable()->update( $this );
		
		$this->clean = $this->row;
		$this->setDirty( false );
	}
	
	/**
	 * Deletes the record from the table
	 * 
	 * @return bool
	 */
	public function delete()
	{
		$pk = $this->getPk();
		if( ! empty( $pk ) )
		{
			if( $this->getTable()->delete( $this ) )
			{
				$this->__destruct();
				
				return true;
			}
		}
		
		return false;
	}
	
	/**
	 * In case one wants a new row containing the same properties the row's
	 * primary key will be nulled and the errors removed
	 */
	public function __clone()
	{
		$this->row[$this->getTable()->getPkField()] = null;
		
		$this->errors = array();
		$this->clean  = $this->row;
		$this->dirty  = false;
	}
	
	/**
	 * Class destructor
	 */
	public function __destruct()
	{
		$this->table  = null;
		$this->row    = null;
		$this->clean  = null;
		$this->dirty  = null;
		$this->errors = null;
	}
	
}

?>
