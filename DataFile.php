<?php

/**
 * Copyright (c) 2007, Gheorghe Constantin-Adrian. All rights reserved.
 * 
 * Code licensed under the BSD License:
 * http://adrian.lastdot.org/license.txt
 */

/**
 * Class <code>DataFile</code> reads record-based textfiles into memory.
 * <p>
 *   The class is very simple, in that it does only 3 things:
 * </p>
 * <ol>
 *   <li>
 *     It reads the complete file specified on construction.
 *   </li>
 *   <li>
 *     It delegates the processing of each line in the the file to a
 *     <code>DataFileReader</code> object
 *   </li>
 *   <li>
 *     It stores the records in an internal array
 *   </li>
 * </ol>
 * <p>
 *   Once a <code>DataFile</code>-object has been created, the methods
 *   <code>getRecordCount</code> and <code>getRecord</code> can be used to get
 *   the total number of records in the file, and a record at a specific index.
 *   Or, for more generic behavior, a <code>DataFileIterator</code> can be used.
 * </p>
 * <p>
 *   Because the actual processing of the file is delegated to an object of
 *   class <code>DataFileReader</code>, class <code>DataFile</code> can be used
 *   for many kinds of files; by simply specifying a different
 *   <code>DataFileReader</code>, a file with a completely different structure
 *   can be read.
 * </p>
 * <p>
 *   On construction not only the filename of the file to read must be
 *   specified; the <code>DataFileReader</code> to use must be passed as well.
 *   In most cases an object of class <code>DataFileReader</code> will do the
 *   job nicely.
 * </p>
 * <p>
 *   Class <code>DataFile</code> is meant for read-only access. Although it's
 *   possible to change records once they are read, these changes cannot be
 *   saved. This functionality is left out, because it's very difficult to
 *   implement. (Take into account that PHP runs in a multi-user environment!)
 * </p>
 * @see DataFileReader
 * @see DataFileIterator
 */
class RPC_DataFile implements IteratorAggregate, Countable
{

	/**
	 * The name of the file to read
	 * 
	 * @var string
	 */
	protected $filename;
	
	/**
	 * The <code>DataFileReader</code> to use
	 * 
	 * @var RPC_DataFile_Reader
	 */
	protected $reader;
	
	/**
	 * The array of records in the file
	 * 
	 * @var array
	 */
	protected $records;
	
	/**
	 * The total number of records in the file
	 * 
	 * @var int
	 */
	protected $count;
	
	/**
	 * Construct a new <code>DataFile</code>
	 * 
	 * @param $filename the name of the file to read
	 * @param $datafilereader the <code>DataFileReader</code> to use
	 */
	public function __construct( $filename, $datafilereader )
	{
		$this->filename = $filename;
		$this->records  = array();
		$this->count    = -1;
		$this->reader   = $datafilereader;
		
		$this->readFile();
	}
	
	/**
	 * Read the file into memory
	 */
	protected function readFile()
	{
		foreach( file( $this->filename ) as $line )
		{
			$line = $this->reader->parseLine( $line );
			if( $line !== false )
			{
				$this->records[] = $line;
			}
		}
		
		$this->count = count( $this->records );
	}
	
	/**
	 * Return the total number of records in the file
	 * 
	 * @return int
	 */
	public function count()
	{
		return $this->count;
	}
	
	/**
	 * Implements IteratorAggregate
	 *
	 * @return RPC_DataFile_Iterator
	 */
	public function getIterator()
	{
		return new RPC_DataFile_Iterator( $this );
	}
	
	/**
	 * Return the record at the specified index
	 * 
	 * @param $index the index of the record to return
	 * 
	 * @return array
	 */
	public function getRecord( $index )
	{
		return isset( $this->records[$index] ) ? $this->records[$index] : '';
	}
	
}

?>
