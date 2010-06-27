<?php

/**
 * Copyright (c) 2007, Gheorghe Constantin-Adrian. All rights reserved.
 * 
 * Code licensed under the BSD License:
 * http://adrian.lastdot.org/license.txt
 */

/**
 * Class which eases communication through sockets
 * 
 * <code>
 * $client = new RPC_Socket_Client();
 * $client->connect( 'www.example.com', 80 );
 * 
 * $buffer  = "HEAD / HTTP/1.1\r\n";
 * $buffer .= "Host: www.google.com\r\n";
 * $buffer .= "Connection: Close\r\n\r\n";
 * 
 * $client->write( $buffer );
 * 
 * echo $client->read( 4096 );
 * 
 * $client->close();
 * </code>
 * 
 * @package Socket
 */
class RPC_Socket_Client extends RPC_Socket
{
	
	/**
	 * Initiates a connection on the socket
	 * 
	 * @param string $addr Either an IP address in dotted-quad notation (e.g. 127.0.0.1), if the socket is of the AF_INET family;
	 *                     or the pathname of a Unix domain socket, if the socket family is AF_UNIX
	 * @param int    $port Only used when connecting to an AF_INET socket, and designates the port on the remote host to which a
	 *                     connection should be made
	 */
	public function connect( $addr, $port = null )
	{
		if( ! @socket_connect( $this->socket, $addr, $port ) )
		{
			$this->throwException();
		}
	}
	
	/**
	 * Read from the socket
	 * 
	 * @param int $length
	 * @param int $type
	 * 
	 * @return string
	 */
	public function read( $length, $type = PHP_BINARY_READ )
	{
		$read = socket_read( $this->socket, $length, $type );
		
		if( $read === false )
		{
			$this->throwException();
		}
		
		return trim( $read );
	}
	
	/**
	 * Write to the socket
	 * 
	 * @param string $buffer
	 */
	public function write( $buffer )
	{
		$length  = strlen( $buffer );
		
		while( ( $written = socket_write( $this->socket, $buffer ) ) !== false )
		{
			if( $written == $length )
			{
				return;
			}
			
			$buffer = substr( $buffer, $written );
			$length = strlen( $buffer );
		}
		
		if( $written < $length )
		{
			$this->throwException();
		}
	}
	
}

?>
