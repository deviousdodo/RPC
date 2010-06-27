<?php

class RPC_View_Filter_Datagrid_Pager implements RPC_View_Filter
{
	
	public function filter( $source )
	{
		$regex  = new RPC_Regex( '/<pager \/>/' );
		$source = $regex->replace( $source, '<?php echo $_rpc_view_datagrid->getPager()->render() ?>' );
		
		return $source;
	}
	
}

?>
