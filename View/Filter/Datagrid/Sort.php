<?php

class RPC_View_Filter_Datagrid_Sort implements RPC_View_Filter
{
	
	public function filter( $source )
	{
		$regex  = new RPC_Regex( '/<sort +field="([^"]+)">(.+?)<\/sort>/' );
		$source = $regex->replace( $source, '<?php echo $_rpc_view_datagrid->printSortBy( \'$1\', \'$2\' ) ?>' );
		
		return $source;
	}
	
}

?>
