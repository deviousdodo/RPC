<?php

/**
 * Copyright (c) 2007, Gheorghe Constantin-Adrian. All rights reserved.
 * 
 * Code licensed under the BSD License:
 * http://adrian.lastdot.org/license.txt
 */

/**
 * Transforms <code><render></render></code> tags into code which will render
 * the file they point to
 * 
 * @package View
 */
class RPC_View_Filter_Render implements RPC_View_Filter
{
	
	/**
	 * Transforms code like
	 * 
	 * <code><render>file.php</render></code>
	 * 
	 * into
	 * 
	 * <code><?php echo $this->render( "file.php" ); </code>
	 * 
	 * @param string $source
	 * 
	 * @return string
	 */
	public function filter( $source )
	{
		$regex = new RPC_Regex( '/<render>([^<]+)<\/render>/' );
		$regex->match( $source, $matches );
		
		foreach( $matches as $match )
		{
			$source = str_replace( $match[0][0], '<?php $_rpc_view_old_template = $view->getCurrentTemplate(); $view->setCurrentTemplate( "' . $match[1][0] . '" ); require $view->getFilteredFile( "' . $match[1][0] . '" ); $view->setCurrentTemplate( $_rpc_view_old_template ); ?>', $source );
		}
		
		return $source;
	}
	
}

?>
