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
	function Array_LongerThan($array, $size)
	{
		return is_array($array) && sizeof($array) > $size;
	}
	
	/**
	 * description
	 * @param param, description.
	 */
	function Array_Remove(&$array, $index, $keepkeys=false)
	{
		$result = false;
		if (is_array($array) && sizeof($array) > $index)
		{
			unset($array[$index]);
			if ($keepkeys !== true) { $array = array_values($array); }
			$result = true;
		}
		return $result;
	}
	
	/**
	 * description
	 * @param param, description.
	 */
	function Array_Contains($array, $string, &$out=null)
	{
		$result = false;
		
		if (is_array($array))
		{
			$result = in_array($string, $array);
			if ($out !== null) { $out = array_search($string, $array); }
		}
		
		return $result;
	}
}
?>
