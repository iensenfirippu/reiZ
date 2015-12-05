<?php
//------------------------------------------------------------
// Project:		reiZ CMS
// License:		GPL v2
//
// Contents:		declaration of static data for use in reiZ CMS
// Created by:		Philip Jensen (me@iensenfirippu.dk)
// Class version:	0.1
// Date:				2015/08/11
//------------------------------------------------------------

// Make sure to check if the script is being run inside "reiZ"
if (defined('reiZ') or exit(1))
{
	// URL related constants
	define("REQUESTEDFILE",		$_SERVER['SCRIPT_FILENAME']);
	define("GETPAGE",				"page");
	define("GETARGS",				"args");
	// Neatness constants, for often repeated values
	define("ZERO",					0);
	define("EMPTYSTRING",		"");
	define("SINGLESPACE",		" ");
	define("SINGLECOMMA",		",");
	define("SINGLEDOT",			".");
	define("SINGLESLASH",		"/");
	define("NEWLINE",				"\n");
	define("INDENT",				"\t");
	define("COMMASPACE",			SINGLECOMMA.SINGLESPACE);
	// Folder names
	define("FOLDERADMIN",		"admin");
	define("FOLDERCACHE",		"cache");
	define("FOLDERCLASSES",		"classes");
	define("FOLDERCOMMON",		"common");
	define("FOLDERCONFIG",		"config");
	define("FOLDERFILES",		"files");
	define("FOLDERINSTALL",		"install");
	define("FOLDERIMAGES",		"images");
	define("FOLDERLAYOUTS",		"layouts");
	define("FOLDERLAYOUT",		FOLDERLAYOUTS); // obsolete: not plural
	define("FOLDERMASTER",		"master");
	define("FOLDERMODULES",		"modules");
	define("FOLDERSCRIPTS",		"scripts");
	define("FOLDERSTYLES",		"styles");
	define("FOLDERTHEMES",		"themes");
	// Number of seconds in specified interval
	define("ONEMINUTE",			60);
	define("-ONEMINUTE",			-60);
	define("ONEHOUR",				3600);
	define("-ONEHOUR",			-3600);
	define("ONEDAY",				86400);
	define("-ONEDAY",				-86400);
	define("ONEWEEK",				604800);
	define("-ONEWEEK",			-604800);
	define("ONEMONTH",			2592000); // not actually a month but 30 days
	define("-ONEMONTH",			-2592000);
	define("ONEYEAR",				31536000); // not actually a year but 365 days
	define("-ONEYEAR",			-31536000);
}
?>
