<?php

class RPC_View_Filter_Placeholder implements RPC_View_Filter
{
	
	public function filter( $source )
	{
		$regex = new RPC_Regex( '/<placeholder +id="([^"]+)" +\/>/' );
		$source = $regex->replace( $source, '<?php if( function_exists( \'_rpc_view_placeholder_${1}\' ) ) { _rpc_view_placeholder_${1}( $this, $_rpc_view_old_template ); } ?>' );
			
		$regex = new RPC_Regex( '/<filler +for="([^"]+)" *>/' );
		$source = $regex->replace( $source, '<?php function _rpc_view_placeholder_${1}( $view, $_rpc_view_old_template ) { $_rpc_view_tmp_old_template = $view->getCurrentTemplate(); $view->setCurrentTemplate( $_rpc_view_old_template ); extract( $view->getVars() ); $form = new RPC_View_Form(); ?>' );
		
		$regex = new RPC_Regex( '#</filler>#' );
		$source = $regex->replace( $source, '<?php $view->setCurrentTemplate( $_rpc_view_tmp_old_template ); } ?>' );
		
		return $source;
	}
	
}

?>
