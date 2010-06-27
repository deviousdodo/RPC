<?php

/**
 * Copyright (c) 2007, Gheorghe Constantin-Adrian. All rights reserved.
 * 
 * Code licensed under the BSD License:
 * http://adrian.lastdot.org/license.txt
 */

/**
 * Base class implementing methods needed in both the client and the server
 * 
 * @see RPC_Socket_Server
 * @see RPC_Socket_Client
 * 
 * @package Socket
 */
abstract class RPC_Socket
{
	
	/**
	 * Socket resource
	 * 
	 * @var resource
	 */
	protected $socket = null;
	
	/**
	 * Class constructor
	 * 
	 * Attempts to create a socket or, if the first parameter is already a
	 * resource, it will set the received resource as the handler. The second
	 * case will be useful when creating sockets directly from resources
	 * returned by socket_accept
	 * 
	 * @param int|resource $domain   Protocol family to be used by the socket or an already existing socket
	 * @param int          $type     Type of communication to be used by the socket
	 * @param int          $protocol Specific protocol within the specified domain to be used when communicating
	 *                               on the returned socket
	 */
	public function __construct( $domain = AF_INET, $type = SOCK_STREAM, $protocol = SOL_TCP )
	{
		if( is_resource( $domain ) )
		{
			$this->socket = $domain;
			return;
		}
		
		if( ! ( $this->socket = socket_create( $domain, $type, $protocol ) ) )
		{
			$this->throwException();
		}
	}
	
	/**
	 * Puts socket in blocking mode
	 * 
	 * @param bool $blocking
	 * 
	 * @return self
	 */
	public function setBlocking( $blocking = false )
	{
		$func = $blocking ? 'socket_set_block' : 'socket_set_nonblock';
		
		if( ! $func( $this->socket ) )
		{
			$this->throwException();
		}
		
		return $this;
	}
	
	/**
	 * Throws an exception containing the error code and corresponding error
	 * message
	 */
	public function throwException()
	{
		$code = socket_last_error( $this->socket );
		throw new RPC_Socket_Exception( socket_strerror( $code ), $code );
	}
	
	/**
	 * Closes the socket
	 */
	public function close()
	{
		if( ! @socket_close( $this->socket ) )
		{
			$this->socket = null;
		}
	}
	
	/**
	 * Class destructor - makes sure the socket has been closed
	 */
	public function __destruct()
	{
		$this->close();
	}
	
}

?>
