<?php
//------------------------------------------------------------
// Project:		reiZ CMS
// License:		GPL v2
//
// Contents:		Extention of basic PHP functionality
// Created by:		Philip Jensen (me@iensenfirippu.dk)
// Class version:	0.1
// Date:				2015/08/10
//------------------------------------------------------------

// Make sure to check if the script is being run inside "reiZ"
if (defined('reiZ') or exit(1))
{
	/**
	 * description
	 * @param param, description.
	 */
	function Boolean_Display($boolean)
	{
		$value;
		if ($boolean == true || $boolean == 1 || $boolean == '1') { $value = 'true'; }
		elseif ($boolean == false || $boolean == 0 || $boolean == '0') { $value = 'false'; }
		else { $value = $boolean; }
		return $value;
	}
	
	/**
	 * description
	 * @param param, description.
	 */
	function Boolean_Flip(&$boolean)
	{
		if (is_bool($boolean))
		{
			$boolean = !$boolean;
		}
	}
}
?>