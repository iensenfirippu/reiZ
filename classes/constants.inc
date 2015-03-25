<?php
/*
 * Defines all the static data for reiZ CMS
 * Copyright 2013 Iensen Firippu <philip@marugawalite>
 */

if (defined('reiZ') or exit(1))
{
	// URL related constants
	define("REQUESTEDFILE",		$_SERVER['SCRIPT_FILENAME']);
	define("GETPAGE",			"page");
	define("GETARGS",			"args");
	// Neatness constants, for often repeated values
	define("ZERO",				0);
	define("EMPTYSTRING",		"");
	define("SINGLESPACE",		" ");
	define("SINGLECOMMA",		",");
	define("SINGLEDOT",			".");
	define("SINGLESLASH",		"/");
	define("NEWLINE",			"\n");
	define("INDENT",			"\t");
	define("COMMASPACE",		SINGLECOMMA.SINGLESPACE);
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
	define("-ONEMINUTE",		-60);
	define("ONEHOUR",			3600);
	define("-ONEHOUR",			-3600);
	define("ONEDAY",			86400);
	define("-ONEDAY",			-86400);
	define("ONEWEEK",			604800);
	define("-ONEWEEK",			-604800);
	define("ONEMONTH",			2592000); // 30 days
	define("-ONEMONTH",			-2592000);
	define("ONEYEAR",			31536000); // 365 days
	define("-ONEYEAR",			-31536000);
}
?>
