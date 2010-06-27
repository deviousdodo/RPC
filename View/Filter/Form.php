<?php

/**
 * Copyright (c) 2007, Gheorghe Constantin-Adrian. All rights reserved.
 * 
 * Code licensed under the BSD License:
 * http://adrian.lastdot.org/license.txt
 */

/**
 * Finds forms in the source and adds a line of code which will set the method
 * for the current form
 * 
 * @package View
 */
class RPC_View_Filter_Form extends RPC_View_Filter_Chain
{
	
	/**
	 * Class Constructor which adds some filters which are part of the form
	 */
	public function __construct()
	{
		 parent::addFilter( new RPC_View_Filter_Form_InputText() );
		 parent::addFilter( new RPC_View_Filter_Form_InputHidden() );
		 parent::addFilter( new RPC_View_Filter_Form_InputRadio() );
		 parent::addFilter( new RPC_View_Filter_Form_InputCheckbox() );
		 parent::addFilter( new RPC_View_Filter_Form_Textarea() );
		 parent::addFilter( new RPC_View_Filter_Form_Select() );
	}
	
	/**
	 * Finds forms in the source and adds a line of code which will set the method
	 * for the current form
	 *
	 * <code><form method="post" action="/path/to/action" class="example"></code>
	 * will become
	 * <code><form method="post" action="/path/to/action" class="example"><?php $form->setMethod( 'post' ); ?></code>
	 * 
	 * @param string $source
	 * 
	 * @return string
	 */
	public function filter( $source )
	{
		$regex  = new RPC_Regex( '/<form.*?method="([^"]+)".*?(?<!\?)>/' );
		$source = $regex->replace( $source, '${0}<?php $form->setMethod( \'${1}\' ); ?>' );
		
		$source = parent::filter( $source );
		
		return $source;
	}
	
}

?>
