<?php

/**
 * Copyright (c) 2007, Gheorghe Constantin-Adrian. All rights reserved.
 * 
 * Code licensed under the BSD License:
 * http://adrian.lastdot.org/license.txt
 */

/**
 * Class which eases the task of creating socket servers.
 * 
 * <code>
 * $server = new RPC_Socket_Server();
 * $server->bind( '127.0.0.1', 1337 );
 * $server->listen( 5 );
 * 
 * while( 1 )
 * {
 *     while( $child = $server->accept() )
 *     {
 *          $pid = pcntl_fork();
 *          
 *          if( $pid == -1 )
 *          {
 *               // Fork failed
 *               $child->close();
 *               break;
 *          }
 *          elseif( $pid == 0 )
 *          {
 *               // Child process
 *               $child->write( 'Welcome to our server' . "\n" );
 *               
 *               while( $str = $child->read( 4096 ) )
 *               {
 *                    $child->write( strrev( $str ) . "\n" );
 *               }
 *               
 *               $child->close();
 *               
 *               exit;
 *           }
 *           else
 *           {
 *               // Parent process
 *               break;
 *           }
 *      }
 * }
 * </code>
 * 
 * @package Socket
 */
class RPC_Socket_Server extends RPC_Socket
{
	
	/**
	 * Binds a name to the socket
	 * 
	 * @param string $addr Either an IP address in dotted-quad notation (e.g. 127.0.0.1), if the socket is of the AF_INET family;
	 *                     or the pathname of a Unix domain socket, if the socket family is AF_UNIX
	 * @param int    $port Only used when connecting to an AF_INET socket, and designates the port on the remote host to which a
	 *                     connection should be made
	 */
	public function bind( $addr, $port = null )
	{
		if( ! socket_bind( $this->socket, $addr, $port ) )
		{
			$this->throwException();
		}
		
		return $this;
	}
	
	/**
	 * Listens for a connection on the socket
	 * 
	 * @param int $backlog Number of incoming connections queued for processing
	 */
	public function listen( $backlog = null )
	{
		if( ! socket_listen( $this->socket, $backlog ) )
		{
			$this->throwException();
		}
	}
	
	/**
	 * Waits for a connection on the socket and returns the client if the
	 * attempt is successful
	 * 
	 * @return RPC_Socket_Client
	 */
	public function accept()
	{
		if( ( $socket = socket_accept( $this->socket ) ) === false )
		{
			$this->throwException();
		}
		
		return new RPC_Socket_Client( $socket );
	}
	
}

?>
