<?php

/**
 * Copyright (c) 2007, Gheorghe Constantin-Adrian. All rights reserved.
 * 
 * Code licensed under the BSD License:
 * http://adrian.lastdot.org/license.txt
 */

/**
 * Implements a basic caching mechanism for views
 * 
 * @package View
 */
class RPC_View_Cache
{
	
	/**
	 * Directory path where cached templates are stored
	 * 
	 * @var string
	 */
	protected $directory = '';
	
	/**
	 * Instantiates an object with a given directory to store templates
	 * 
	 * @param string $path
	 */
	public function __construct( $path )
	{
		$this->setDirectory( $path );
	}
	
	/**
	 * Sets the path where templates will be cached
	 * 
	 * @param string $path
	 * 
	 * @return RPC_View_Cache
	 */
	public function setDirectory( $path )
	{
		$path = realpath( $path );
		
		if( ! is_dir( $path ) )
		{
			throw new Exception( $path . ' is not a directory' );
		}
		
		if( ! is_writable( $path ) )
		{
			throw new Exception( $path . ' must be writeable' );
		}
		
		$this->directory = $path;
		
		return $this;
	}
	
	/**
	 * Returns the directory where cached templates are stored
	 * 
	 * @return string
	 */
	public function getDirectory()
	{
		return $this->directory;
	}
	
	/**
	 * Returns a path to the cached version of the given template
	 * 
	 * @param string $file
	 * 
	 * @return string
	 */
	public function get( $file )
	{
		$path = $this->getPathForFile( $file );
		
		if( ! file_exists( $path ) )
		{
			return false;
		}
		
		if( filemtime( $file ) > filemtime( $path ) )
		{
			@unlink( $path );
			return false;
		}
		
		return $path;
	}
	
	/**
	 * Generates the path where a certain file will be written
	 * 
	 * @param string $file
	 * 
	 * @return string
	 */
	protected function getPathForFile( $file )
	{
		return $this->getDirectory() . DIRECTORY_SEPARATOR . 'rpc_view_cache_' . md5( $file ) . '.php';
	}
	
	/**
	 * Caches the content of a template
	 * 
	 * @param string $file
	 * @param string $content
	 * 
	 * @return RPC_View_Cache
	 */
	public function set( $file, $content )
	{
		if( ! file_put_contents( $this->getPathForFile( $file ), $content ) )
		{
			throw new Exception( 'Cannot write cached version of template "' . $file  . '" to "' . $this->getPathForFile( $file ) . '"' );
		}
		
		return $this;
	}

	/**
	 * Removes all cache files from the set directory
	 */
	public function removeCacheFiles()
	{
		foreach( new DirectoryIterator( $this->getDirectory() ) as $entry )
		{
			if( $entry->isFile() &&
			    strpos( $entry->getPathName(), 'rpc_view_cache_' ) !== false )
			{
				@unlink( $entry->getPathName() );
			}
		}
	}
	
}

?>
