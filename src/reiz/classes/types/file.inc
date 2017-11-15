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
	function File_Write($file, $string, $overwrite=false) // Writes a string to a file
	{
		if ($overwrite && file_exists($file)) { unlink($file); }
		$fp = fopen($file,'a');
		// NEWLINE removed because it caused NEWLINE to be inserted into output
		//fwrite($fp,$string.NEWLINE);
		fwrite($fp,$string);
		fclose($fp);
	}
	
	/**
	 * description
	 * @param param, description.
	 */
	function File_CalculateSize($bytes)
	{
		$value = EMPTYSTRING;
		if ($bytes < 1024)
		{
			$value = $bytes.' bytes';
		}
		else if ($bytes < 1048576)
		{
			$value = number_format(($bytes / 1024), 1, ',', '').' KiB';
		}
		else
		{
			$value = number_format(($bytes / 1048576), 1, ',', '').' MiB';
		}
		return $value;
	}
}
?>