<?php

/**
 * Copyright (c) 2007, Gheorghe Constantin-Adrian. All rights reserved.
 * 
 * Code licensed under the BSD License:
 * http://adrian.lastdot.org/license.txt
 */

/**
 * Transforms inputs like:
 * <code><input type="checkbox" name="u[areaofinterest][<?= $id ?>]" id="areaofinterest<?= $id ?>" value="<?= $area ?>" default="$user->areasofinterest[$id]" /></code>
 * 
 * into
 * 
 * <code><input type="text" name="u[username]" id="username" value="<?php if( isset( $this->getRequest()->post['u']['username'] ) ): echo $this->getRequest()->post['u']['username']; else: ?><?= $user->username ?><?php endif; ?>" /></code>
 * (considering that the form's method is post)
 * 
 * @package View
 */
class RPC_View_Filter_Form_InputCheckbox extends RPC_View_Filter_Form_Element implements RPC_View_Filter
{
	
	/**
	 * Adds persistence code to all checkbox inputs inside the given form
	 * 
	 * @param string $source
	 * 
	 * @return string
	 */
	public function filter( $source )
	{
		$regex = new RPC_Regex( '/<input.*?type="checkbox".*?\/>/' );
		$regex->match( $source, $inputs );
		
		foreach( $inputs as $input )
		{
			$input = $input[0][0];
			
			$name    = $this->getAttribute( $input, 'name' );
			$value   = $this->getAttribute( $input, 'value' );
			$checked = $this->getAttribute( $input, 'checked' );
			
			$new_input = $this->setAttribute( $input, 'checked', '<?php echo $form->checkbox( ' . $name . ', ' . $value . ', ' . $checked . ' ) ?>' );
			
			$source = str_replace( $input, $new_input, $source );
		}
		
		return $source;
	}
	
}

?>
