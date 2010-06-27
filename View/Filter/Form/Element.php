<?php

/**
 * Copyright (c) 2007, Gheorghe Constantin-Adrian. All rights reserved.
 * 
 * Code licensed under the BSD License:
 * http://adrian.lastdot.org/license.txt
 */

class RPC_View_Filter_Form_Element
{
	
	/**
	 * Checks to see if the attribute exists on the form element
	 * 
	 * @param string $html Form element
	 * @param string $name Attribute name
	 * 
	 * @return bool
	 */
	public function hasAttribute( $html, $name )
	{
		return ( strpos( $html, $name . '="' ) !== false );
	}
	
	/**
	 * Returns the value of an attribute for an element
	 * 
	 * In case the attribute is actually a PHP expression, it will
	 * return the expression itself, just as it appears in the text.
	 * If the attribute is simply text, then it will return it quoted.
	 * 
	 * @param string $html HTML element
	 * @param string $name Attribute name
	 * 
	 * @return string
	 */
	public function getAttribute( $html, $name )
	{
		/*
		 * If the attribute is a php value, then I have to match until the closing
		 * ", but only if it's preceded by ?>
		 */
		if( strpos( $html, $name . '="<' ) !== false )
		{
			$regex = new RPC_Regex( '/' . $name . '="<\?=(.*?)(?<=\?>")/' );
			if( ! $regex->match( $html, $matches ) )
			{
				return "''";
			}
			
			return trim( substr( $matches[0][1][0], 0, -3 ) );
		}
		
		$regex = new RPC_Regex( '/' . $name . '="([^"]+)"/' );
		if( ! $regex->match( $html, $matches ) )
		{
			return "''";
		}
		
		return "'" . str_replace( "'", "\'", trim( $matches[0][1][0] ) ) . "'";
	}
	
	/**
	 * Removes an attribute from the element
	 * 
	 * @param string $html
	 * @param string $name
	 * 
	 * @return string Element markup without the attribute
	 */
	public function removeAttribute( $html, $name )
	{
		if( strpos( $html, $name . '="<' ) !== false )
		{
			$regex = new RPC_Regex( '/' . $name . '="<\?=.*?(?<=\?>")/' );
		}
		else
		{
			$regex = new RPC_Regex( '/' . $name . '="[^"]+"/' );
		}
		
		return $regex->replace( $html, '' );
	}
	
	/**
	 * Adds an attribute with a give value to a form element. If the
	 * attribute already exists it will be overwritten
	 * 
	 * @param string $html  Form element
	 * @param string $name  Attribute name
	 * @param string $value New value for the attribute
	 * 
	 * @return string
	 */
	public function setAttribute( $html, $name, $value )
	{
		/**
		 * The checked attribute gets special treatment, because you cannot
		 * give it a value and make it not selected, so it should only be
		 * present if it was really checked
		 * 
		 * This should be refactored to a separate method if more exceptions
		 * appear, but for now it works fine
		 */
		if( $name == 'checked' )
		{
			$html = $this->removeAttribute( $html, 'checked' );
			
			$regex = new RPC_Regex( '#/>$#' );
			return $regex->replace( $html, ' ' . $value . ' />' );
		}
		
		/*
		 * If attribute is a PHP expression
		 */
		if( strpos( $html, $name . '="<' ) !== false )
		{
			$regex = new RPC_Regex( '/' . $name . '="<\?=.*?(?<=\?>")/' );
			return $regex->replace( $html, $name . '="' . $value . '"' );
		}
		elseif( strpos( $html, $name . '="' ) !== false )
		{
			$regex = new RPC_Regex( '/' . $name . '="[^"]*"/' );
			return $regex->replace( $html, $name . '="' . $value . '"' );
		}
		
		/*
		 * If the attribute does not exist on the element, we add it
		 */
		
		/*
		 * If the element is not a textarea or select, we add the attribute
		 * just before it's end
		 */
		if( substr( $html, -2 ) == '/>' )
		{
			$regex = new RPC_Regex( '#/>$#' );
			return $regex->replace( $html, ' ' . $name . '="' . $value . '" />' );
		}
		
		/*
		 * Otherwise the attribute is added just after the attribute name
		 */
		$regex = new RPC_Regex( '#<(select|textarea)#' );
		return $regex->replace( $html, '<$1 ' . $name . '="' . $value . '" ' );
	}
	
	/**
	 * Sets the content for select and textareas
	 * 
	 * @param string $html    Form element
	 * @param string $content New content
	 * 
	 * @return string
	 */
	public function setContent( $html, $content )
	{
		$regex = new RPC_Regex( '#>(</(textarea|select)>)$#' );
		return $regex->replace( $html, '>' . $content . '$1' );
	}
	
}

?>
