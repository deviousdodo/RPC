<?php

/**
 * Copyright (c) 2007, Gheorghe Constantin-Adrian. All rights reserved.
 * 
 * Code licensed under the BSD License:
 * http://adrian.lastdot.org/license.txt
 */

/**
 * Used to render HTML content
 * 
 * @package View
 */
class RPC_View extends RPC_View_Filter_Chain
{
	
	/**
	 * Assigned variables throughout the script
	 * 
	 * @var array
	 */
	protected $_view_vars = array();
	
	/**
	 * Cache object
	 * 
	 * @var object
	 */
	protected $_view_cache = null;
	
	/**
	 * Base directory for templates
	 * 
	 * @var string
	 */
	protected $_view_tpldir = '';
	
	/**
	 * Default filters
	 * 
	 * @var array
	 */
	protected $_view_defaultfilters = array
	(
		'RPC_View_Filter_Form',
		'RPC_View_Filter_Echo',
		'RPC_View_Filter_Render',
		'RPC_View_Filter_Placeholder',
		'RPC_View_Filter_Comment',
		'RPC_View_Filter_Whitespace',
		'RPC_View_Filter_Relativepath',
		'RPC_View_Filter_Datagrid',
		'RPC_View_Filter_Error'
	);
	
	/**
	 * Registered filters
	 * 
	 * @var array
	 */
	protected $_view_registeredfilters = array();
	
	/**
	 * Template that is currently being rendered
	 * 
	 * @var string
	 */
	protected $current_template = '';
	
	/**
	 * HTTP Request object
	 * 
	 * @var RPC_HTTP_Request
	 */
	public $request;
	
	/**
	 * HTTP Response object
	 * 
	 * @var RPC_HTTP_Response
	 */
	public $response;
	
	/**
	 * Class constructor which adds the default filters and some needed
	 * variables
	 */
	public function __construct( $dir, RPC_View_Cache $cache )
	{
		if( ! is_dir( $dir ) )
		{
			throw new Exception( 'The given path does not point to a directory' );
		}
		
		if( ! is_object( $cache ) )
		{
			throw new Exception( 'You must set a cache object' );
		}
		
		$this->_view_tpldir = realpath( $dir );
		$this->_view_cache  = $cache;
		
		$this->setRequest( RPC_HTTP_Request::getInstance() );
		$this->setResponse( RPC_HTTP_Response::getInstance() );
		
		foreach( $this->_view_defaultfilters as $v )
		{
			$this->registerFilter( $v );
		}
	}
	
	/**
	 * Returns the set template directory
	 * 
	 * @return string
	 */
	public function getTemplateDirectory()
	{
		return $this->_view_tpldir;
	}
	
	/**
	 * Set the HTTP Response object
	 * 
	 * @param RPC_HTTP_Response $response
	 */
	public function setResponse( $response )
	{
		$this->response = $response;
	}
	
	/**
	 * Set the HTTP Response object
	 * 
	 * @param RPC_HTTP_Response $response
	 */
	public function getResponse( $response )
	{
		return $this->response;
	}
	
	/**
	 * Set the HTTP Request object
	 * 
	 * @param RPC_HTTP_Request $request
	 */
	public function setRequest( $request )
	{
		$this->request = $request;
	}
	
	/**
	 * Returnt the HTTP Request object
	 * 
	 * @param RPC_HTTP_Request $request
	 */
	public function getRequest()
	{
		return $this->request;
	}
	
	/**
	 * Escapes all HTML characters from the given string
	 * 
	 * @param string $str
	 * 
	 * @return string Escaped string
	 */
	public function escape( $str )
	{
		return htmlentities( $str, ENT_QUOTES, 'UTF-8', false );
	}
	
	/**
	 * Returns the array of defined variables
	 *
	 * @return array
	 */
	public function getVars()
	{
		return $this->_view_vars;
	}
	
	/**
	 * Registers a filter with the view, and in case the filter has external
	 * functionality (for example, the RPC_View_Error filter has to be accessed
	 * from outside, so that errors can be set and fetched) provides a name
	 * which will allow access to the object
	 * 
	 * @param string $filter
	 * @param string $name
	 */
	public function registerFilter( $class_name )
	{
		$name = explode( '_', $class_name );
		$name = strtolower( end( $name ) );
		
		$this->_view_registeredfilters[$name] = array( 'class_name' => $class_name, 'instance' => null );
		
		return $this;
	}
	
	/**
	 * Removes all filters registered in the constructor
	 */
	public function removeDefaultFilters()
	{
		foreach( $this->_view_defaultfilters as $v )
		{
			$name = explode( '_', $v );
			$name = end( $name );
			
			unset( $this->_view_registeredfilters[$name] );
		}
	}
	
	/**
	 * Unregisters a filter from the queue
	 * 
	 * @param string $filter
	 * 
	 * @return RPC_View
	 */
	public function unregisterFilter( $filter )
	{
		$name = explode( '_', $filter );
		$name = end( $name );
		
		unset( $this->_view_registeredfilters[$name] );
		
		return $this;
	}
	
	/**
	 * Returns the parser's cache object
	 * 
	 * @return RPC_View_Cache
	 */
	public function getCache()
	{
		return $this->_view_cache;
	}
	
	/**
	 * Assigns a variable which will be available in the templates
	 * 
	 * @param string $var
	 * @param mixed  $value
	 */
	public function __set( $var, $value )
	{
		if( strpos( $var, 'plugin_' ) === 0 )
		{
			throw new Exception( 'You are trying to assign a value on an attribute which is reserved to a filter' );
		}
		
		$this->_view_vars[$var] = $value;
	}
	
	/**
	 * Returns an assigned variable or null if the variable does not exist
	 * 
	 * @param string $var
	 * 
	 * @return mixed
	 */
	public function __get( $var )
	{
		if( strpos( $var, 'plugin_' ) === 0 )
		{
			$var = substr( $var, 7 );
			
			if( empty( $this->_view_registeredfilters[$var]['instance'] ) )
			{
				$class = $this->_view_registeredfilters[$var]['class_name'];
				$this->_view_registeredfilters[$var]['instance'] = new $class;
			}
			
			return $this->_view_registeredfilters[$var]['instance'];
		}
		
		return isset( $this->_view_vars[$var] ) ? $this->_view_vars[$var] : null;
	}
	
	/**
	 * Checks to see if a certain variable has been assigned
	 * 
	 * @param string $var
	 * 
	 * @return bool
	 */
	public function __isset( $var )
	{
		return isset( $this->_view_vars[$var] );
	}
	
	/**
	 * Returns the output of the template
	 * 
	 * @return string
	 * 
	 * @see self::display
	 */
	public function render( $template )
	{
		ob_start();
		$this->display( $template );
		$output = ob_get_clean();
		
		return $output;
	}

	/**
	 * Parses the template, if it's not already cached and executes it
	 * 
	 * @param string $template Path to template
	 */
	public function display( $template )
	{
		if( ! RPC_Signal::emit( array( 'RPC_View', 'onBeforeRender' ), array( $this, $template ) ) )
		{
			return '';
		}
		
		$this->setCurrentTemplate( $template );
		
		$form = new RPC_View_Form();
		$view = $this;
		
		extract( $this->_view_vars );
		
		/*
			"require"-ing the php file so that the PHP code is ran within the
			local context, which will make the variables (previously extracted)
			available without using $this->
		*/
		require $this->getFilteredFile( $template );
		
		RPC_Signal::emit( array( 'RPC_View', 'onAfterRender' ), array( $this, $template ) );
		
		if( $this->_view_development )
		{
			$this->getCache()->removeCacheFiles();
		}
	}
	
	/**
	 * Returns the path to the filtered template
	 * 
	 * @return string
	 */
	public function getFilteredFile( $template )
	{
		$file = $this->getTemplateDirectory() . DIRECTORY_SEPARATOR . $template;
		
		if( ! file_exists( $file ) )
		{
			throw new Exception( 'File "' . $file . '" does not exist' );
		}
		
		if( ! $this->getCache()->get( $file ) )
		{
			$this->_view_filters = array();
			
			foreach( $this->_view_registeredfilters as & $v )
			{
				if( ! $v['instance'] )
				{
					$class = $v['class_name'];
					$v['instance'] = new $class();
				}
				
				parent::addFilter( $v['instance'] );
			}
			
			$this->getCache()->set( $file, $this->filter( file_get_contents( $file ) ) );
		}
		
		return $this->getCache()->get( $file );
	}
	
	public function getCurrentTemplate( )
	{
		return $this->current_template;
	}
	
	public function setCurrentTemplate( $tpl )
	{
		$this->current_template = $tpl;
		return $this;
	}
	
}

?>
