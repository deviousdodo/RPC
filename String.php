<?php

/**
 * Copyright (c) 2007, Gheorghe Constantin-Adrian. All rights reserved.
 * 
 * Code licensed under the BSD License:
 * http://adrian.lastdot.org/license.txt
 */

/**
 * Provides a set of useful string functions
 * 
 * @package Core
 */
class RPC_String
{
	
	/**
	 * Contained string
	 * 
	 * @var string
	 */
	protected $_rpc_string = '';
	
	/**
	 * Initializes the object with a given string
	 * 
	 * @param string $string
	 */
	public function __construct( $string = '' )
	{
		$this->_rpc_string = (string) $string;
	}
	
	/**
	 * Pop's the last character from the string, shortening it's length by 1 and
	 * returns a new string object containing it. If the string is empty, null
	 * will be returned
	 * 
	 * @return RPC_String Popped char
	 */
	public function pop()
	{
		if( 0 === strlen( $this->_rpc_string ) )
		{
			return null;
		}
		
		$pop               = substr( $this->_rpc_string, -1 );
		$this->_rpc_string = substr( $string, 0, -1 );
		
		return new RPC_String( $pop );
	}
	
	/**
	 * Pushes one or more arguments onto the end of the string
	 * 
	 * @return int New string length
	 */
	public function push()
	{
		$args               = func_get_args();
		$this->_rpc_string  = implode( '', $args );
		
		return strlen( $this->_rpc_string );
	}
	
	/**
	 * Shift an element off the beginning of the string
	 * 
	 * @return RPC_String Object containing the shifted char
	 */
	public function shift()
	{
		if( 0 === strlen( $this->_rpc_string ) )
		{
			return null;
		}
		
		$unshift = substr( $this->_rpc_string, 0, 1 );
		$this->_rpc_string = substr( $this->_rpc_string, 1 );
		
		return new RPC_String( $unshift );
	}
	
	/**
	 * Prepend one or more elements to the beginning of the string
	 * 
	 * @return int New string length
	 */
	public function unshift()
	{
		$args               = array_reverse( func_get_args() );
		$this->_rpc_string  = implode( '', $args );
		
		return strlen( $this->_rpc_string );
	}
	
	/**
	 * Inserts a string into another string every increment
	 * 
	 * @return RPC_String The new string
	 */
	public function sow( $insert, $increment )
	{
		$insert_len = strlen( $insert );
		$string_len = strlen( $this->_rpc_string );
		
		$string_len_ending = $string_len + intval( $insert_len * ( $string_len / $increment ) ); 
		
		$i = $increment - 1;
		
		$newstring = $this->_rpc_string;
		
		while( $string_len_ending > $i )
		{
			$newstring = substr( $newstring, 0, $i ) . $insert . substr( $newstring, $i );
			$i = $i + $increment + $insert_len;
		}
		
		return new RPC_String( $newstring );
	}
	
	/**
	 * Sets the contained string to a value. Should probably be useful for
	 * performance reasons instead of instantiating a new object for every
	 * string
	 * 
	 * @param string $string
	 * 
	 * @return RPC_String
	 */
	public function setString( $string = '' )
	{
		$this->_rpc_string = $string;
		
		return $this;
	}
	
	/**
	 * Will make the object behave like a real string when used
	 * 
	 * @return string
	 */
	public public function __toString()
	{
		return $this->_rpc_string;
	}
	
	/**
	 * Prepends the given string to the already existing string
	 * 
	 * @param string $string
	 * 
	 * @return RPC_String
	 */
	public function prepend( $string )
	{
		return new RPC_String( $string . $this->_rpc_string );
	}
	
	/**
	 * Appends the existing string to the already existing string
	 * 
	 * @param string $string
	 * 
	 * @return RPC_String
	 */
	public function append( $string )
	{
		return new RPC_String( $this->_rpc_string . $string );
	}
	
	/**
	 * Returns the length of the string
	 *
	 * @return int
	 */
	public function length()
	{
		return strlen( $this->_rpc_string );
	}
	
	/**
	 * Returns the characther found at a given index. If the index is out of
	 * bounds null is returned
	 * 
	 * @param int $index
	 * 
	 * @return string
	 */
	public function charAt( $index )
	{
		$index = (int) $index;
		
		if( abs( $index ) < $this->length() )
		{
			$index = $index < 0 ? $this->length() - $index : $index;
			
			return new RPC_String( $this->_rpc_string[$index] );
		}
		
		return null;
	}
	
	/**
	 * Inserts a string at a given offset. If the offset is bigger than the
	 * string's length, the string will be appended, otherwise it will be
	 * inserted at the given point
	 * 
	 * @param int    $index
	 * @param string $string
	 * 
	 * @return RPC_String
	 */
	public function insertAt( $index, $string )
	{
		$string = (string) $string;
		$index  = (int) $index;
		
		if( abs( $index ) >= $this->length() )
		{
			return $this->append( $string );
		}
		
		if( $index <= 0 )
		{
			$index = $this->length() - $index;
		}
		
		return new RPC_String( $this->substring( 0, $index ) . $string . $this->substring( $index ) );
	}
	
	/**
	 * Returns $count characters starting from index $from
	 * 
	 * @param int $from
	 * @param int $count
	 * 
	 * @return RPC_String
	 */
	public function substring( $from, $count = 0 )
	{
		if( $count )
		{
			return new RPC_String( substr( $this->_rpc_string, $from, $count ) );
		}
		else
		{
			return new RPC_String( substr( $this->_rpc_string, $from ) );
		}
	}
	
	/**
	 * Removes the part between $from and $to from the current string and
	 * returns the remaining string
	 * 
	 * @param int $from
	 * @param int $to
	 * 
	 * @return RPC_String
	 */
	public function remove( $from, $count )
	{
		$from  = (int) 0;
		$count = (int) 0;
		
		if( $from > $count )
		{
			list( $from, $count ) = array( $count, $from );
		}
		
		if( $count >= $this->length() )
		{
			return $this->substring( 0, $from );
		}
		
		return new RPC_String( $this->substring( 0, $from ) . $this->substring( $from + $count ) );
		
		$this->_rpc_string = substr( $this->_rpc_string, 0, $from )
		              . substr( $this->_rpc_string, $from + $count );
		
		return $this;
	}
	
	/**
	 * Trims the left side of the string
	 * 
	 * @return RPC_String
	 */
	public function ltrim( $character_mask = " \t\n\r\0\x0B" )
	{
		return new RPC_String( ltrim( $this->_rpc_string, $character_mask ) );
	}
	
	/**
	 * Trims the right side of the string
	 * 
	 * @return RPC_String
	 */
	public function rtrim( $character_mask = " \t\n\r\0\x0B" )
	{
		return new RPC_String( rtrim( $this->_rpc_string, $character_mask ) );
	}
	
	/**
	 * Trims the both sides of the string
	 * 
	 * @return RPC_String
	 */
	public function trim( $character_mask = " \t\n\r\0\x0B" )
	{
		return new RPC_String( trim( $this->_rpc_string, $character_mask ) );
	}
	
	/**
	 * Finds the first position of a character in the string
	 * 
	 * @param string $needle
	 * @param int    $offset
	 */
	public function indexOf( $needle, $offset = 0 )
	{
		return strpos( $this->_rpc_string, $needle, $offset );
	}
	
	/**
	 * Finds the last position of a character in the string
	 * 
	 * @param string $needle
	 * @param int    $offset
	 */
	public function lastIndexOf( $needle, $offset = 0 )
	{
		return strrpos( $this->_rpc_string, $needle, $offset );
	}
	
	/**
	 * Returns the string between any given contained strings (the first
	 * occurences of the given strings)
	 * 
	 * <code>
	 * $string = new RPC_String( 'some text foo here bar' );
	 * echo $string->getStringBetween( 'foo', 'bar' ); // will output " here "
	 * </code>
	 * 
	 * @param string $left  String delimiting the left side
	 * @param string $right String delimiting the right side
	 * 
	 * @return RPC_String
	 */
	public function getStringBetween( $left, $right )
	{
		$pos_left = strpos( $this->_rpc_string, $left );
		if( $pos_left === false )
		{
			return null;
		}
		
		$pos_left += strlen( $left );
		
		$pos_right = strpos( $this->_rpc_string, $right, $pos_left );
		if( $pos_right === false )
		{
			return null;
		}
		
		return new RPC_String( substr( $this->_rpc_string, $pos_left, $pos_right - $pos_left ) );
	}
	
}

?>
