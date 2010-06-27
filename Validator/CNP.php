<?php

/**
 * Copyright (c) 2007, Gheorghe Constantin-Adrian. All rights reserved.
 * 
 * Code licensed under the BSD License:
 * http://adrian.lastdot.org/license.txt
 */

class RPC_Validator_CNP extends RPC_Validator
{
	
	/**
	 * Returns true if the given value is an cnp type
	 *
	 * @param mixed $value
	 * @return bool
	 */
	public function validate( $value )
	{
		$value = (string) $value;
		
		if( strlen( $value ) != 13 )
		{
			return false;
		}
		
		$first_letter = substr( $value, 0, 1 );
		
		list( $year, $month, $day ) = str_split( substr( $value, 1, 7 ), 2 );
		switch( $first_letter )
		{
			case '1':
				$year = '19' . $year;
				break;
			case '2':
				$year = '19' . $year;
				break;
			case '5':
				$year = '20' . $year;
				break;
			case '6':
				$year = '20' . $year;
				break;
			default:
				return false;
		}
		if( ! RPC_Date::validDate( $year . '-' . $month . '-' . $day ) ||
		    RPC_Date::getTimestamp( $year . '-' . $month . '-' . $day ) >= RPC_Date::getTimestamp( date( 'Y-m-d' ) ) )
		{
			return false;
		}
		
		return true;
	}
	
}

?>
