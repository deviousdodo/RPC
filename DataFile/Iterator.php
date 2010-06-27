<?php

/**
 * Copyright (c) 2007, Gheorghe Constantin-Adrian. All rights reserved.
 * 
 * Code licensed under the BSD License:
 * http://adrian.lastdot.org/license.txt
 */

/**
 * Implements the iterator pattern for RPC_DataFile objects.
 * 
 * @see RPC_DataFile
 */
class RPC_DataFile_Iterator implements Iterator
{
	
	/**
	 * The <code>RPC_DataFile</code> whose contents must be iterated over
	 * 
	 * @var RPC_DataFile
	 */
	protected $datafile;
	
	/**
	 * The current index
	 * 
	 * @var int
	 */
	protected $current;
	
	/**
	 * The total number of records
	 * 
	 * @var int
	 */
	protected $max;
	
	/**
	 * Class constructor
	 * 
	 * @param $datafile The RPC_DataFile to iterate over
	 */
	public function __construct( $datafile )
	{
		$this->datafile = $datafile;
		$this->max      = count( $datafile );
		$this->rewind();
	}
	
	/**
	 * @return void
	 */
	public function rewind()
	{
		$this->current = 0;
	}
	
	/**
	 * Advances the internal pointer
	 */
	public function next()
	{
		$this->current++;
	}
	
	/**
	 * @return bool
	 */
	public function valid()
	{
		return $this->current < $this->max;
	}
	
	public function key()
	{
		return $this->current;
	}
	
	/**
	 * @return array
	 */
	public function current()
	{
		return $this->datafile->getRecord( $this->current );
	}
	
}

?>
