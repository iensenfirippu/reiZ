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
	function Object_Unserialize($text)
	{
		return unserialize(gzuncompress(base64_decode($text)));
	}
	
	/**
	 * description
	 * @param param, description.
	 */
	function Object_Serialize($object)
	{
		return base64_encode(gzcompress(serialize($object)));
	}
}
?>
