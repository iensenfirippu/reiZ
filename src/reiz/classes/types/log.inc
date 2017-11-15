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
	function Log_Source($source) // Write html source to file
	{
		File_Write('source.txt', $source, true);
	}
	
	/**
	 * description
	 * @param param, description.
	 */
	function Log_Error($message) // Logs an error message to the log file
	{
		$_SESSION['MESSAGE'] = $message;
		File_Write('errorlog.txt', $message);
	}
}
?>