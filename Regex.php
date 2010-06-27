<?php

/**
 * Copyright (c) 2007, Gheorghe Constantin-Adrian. All rights reserved.
 * 
 * Code licensed under the BSD License:
 * http://adrian.lastdot.org/license.txt
 */

/**
 * Regular expressions class
 * 
 * Please note that all the regexes below are aproximations, all of them are
 * considered "good enough" for most cases, but email validation, for example,
 * is not a simple matter of matching against a regex
 * 
 * @package Core
 */
class RPC_Regex
{
	
	/**
	 * Should match a HTTP, FTP & HTTPS URI resource
	 */
	const URI = '/^(http|ftp|https):\/\/[\w\-_]+(\.[\w\-_]+)+([\w\-\.,@?^=%&:\/~\+#]*[\w\-\@?^=%&\/~\+#])?$/';
	
	/**
	 * Should match a person's name
	 */
	const NAME = '/^[a-zA-Z0-9\&\.\(\)\+\=\@\!\*\'\"\# -]{3,64}$/';
	
	/**
	 * Should match an application username
	 */
	const USERNAME = '/^[a-zA-Z]{1}[a-zA-Z0-9]{2,31}$/';
	
	/**
	 * Should match any (3 digit representation of a) currency
	 */
	const CURRENCY =  '/^[a-zA-Z]{3}$/';
	
	/**
	 * Should match a password
	 */
	const PASSWORD = '/^[[:alnum:]]+$/';
	
	/**
	 * Should match an email address
	 */
	const EMAIL = '/^(\w|[-])+(\.(\w|[-])+)*@((\[([0-1]?\d?\d|2[0-4]\d|25[0-5])\.([0-1]?\d?\d|2[0-4]\d|25[0-5])\.([0-1]?\d?\d|2[0-4]\d|25[0-5])\.([0-1]?\d?\d|2[0-4]\d|25[0-5])\])|((([a-zA-Z0-9])+(([-])+([a-zA-Z0-9])+)*\.)+([a-zA-Z])+(([-])+([a-zA-Z0-9])+)*))$/';
	
	/**
	 * Used to split a CSV line into parts
	 */
	const CSV_LINE = '#,(?=([^"]*"[^"]*")*(?![^"]*"))#';
	
	/**
	 * Regular expression against which all values will be matched
	 * 
	 * @var string
	 */
	protected $regex = null;
	
	/**
	 * Class constructor which sets the regex
	 * 
	 * @param string $regex
	 */
	public function __construct( $regex )
	{
		$this->regex = $regex;
	}
	
	/**
	 * Returns the interal regex
	 * 
	 * @return string
	 */
	public function getRegex()
	{
		return $this->regex;
	}
	
	/**
	 * Matches the given value against the regex
	 * 
	 * Parameters are the same as with <code>preg_match_all</code>
	 * 
	 * $matches[0] is an array of first set of matches, $matches[1] is an array
	 * of second set of matches, and so on. For every occurring match the
	 * appendant string offset will also be returned:
	 * <code>
	 * array
	 * (
	 *     0 => array
	 *     (
	 *         0 => array
	 *         (
	 *             0 => entire match
	 *             1 => offset
	 *         ),
	 *         1 => array
	 *         (
	 *             0 => first subpattern
	 *             1 => offset
	 *         ),
	 *         .
	 *         .
	 *         .
	 *     )
	 *     .
	 *     .
	 *     .
	 * )
	 * </code>
	 * 
	 * @param string $subject
	 * @param array  $matches
	 * @param int    $offset
	 * 
	 * @return bool
	 */
	public function match( $subject, & $matches = array(), $offset = 0 )
	{
		return preg_match_all( $this->regex, $subject, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE, $offset );
	}
	
	/**
	 * Replaces portions of the string which match the regex with $replacement
	 * 
	 * @param string $string
	 * @param string $replacement
	 * @param int    $limit
	 * @param int    $count
	 * 
	 * @return string
	 */
	public function replace( $subject, $replacement, $limit = -1, & $count = 0 )
	{
		return preg_replace( $this->regex, $replacement, $subject, $limit, $count );
	}
	
	/**
	 * Returns the object's regex 
	 * 
	 * @return string
	 */
	public function __toString()
	{
		return $this->regex;
	}
	
}

?>
