<?php
/*
 * Defines, Includes and Initializes all static data
 * Copyright 2013 Iensen Firippu <philip@marugawalite>
 */
// TODO: Split up "defines" into actual defines one or more relevant "start/run/reiZ"
// TODO: Move defines etc. to classes

if (defined('reiZ') or exit(1))
{
	define("ZERO",				0);
	define("EMPTYSTRING",		"");
	define("REQUESTEDFILE",		$_SERVER['SCRIPT_FILENAME']);
	define("GETPAGE",			"page");
	define("GETARGS",			"args");
	// Folder names
	define("FOLDERADMIN",		"admin");
	define("FOLDERCLASSES",		"classes");
	define("FOLDERCOMMON",		"common");
	define("FOLDERCONFIG",		"config");
	define("FOLDERFILES",		"files");
	define("FOLDERMASTER",		"master");
	define("FOLDERMODULES",		"modules");
	define("FOLDERSCRIPTS",		"scripts");
	define("FOLDERSTYLES",		"styles");
	define("FOLDERTHEMES",		"themes");
	define("FOLDERIMAGES",		"images");
	// Number of seconds in specified interval
	define("ONEMINUTE",			60);
	define("-ONEMINUTE",		-60);
	define("ONEHOUR",			3600);
	define("-ONEHOUR",			-3600);
	define("ONEDAY",			86400);
	define("-ONEDAY",			-86400);
	// Number of seconds that has passed on the current day
	define("TODAYSTIME",		((date('G', STARTTIME) * ONEMINUTE) * ONEMINUTE) +
								(date('i', STARTTIME) * ONEMINUTE) +
								date('s', STARTTIME));

	include_once(FOLDERCONFIG."/config.cfg.inc");
	include_once(FOLDERCONFIG."/default.cfg.inc");
	
	if (DISPLAYERRORS) {
		error_reporting(E_ALL);
		ini_set('display_errors', '1');
	}
	date_default_timezone_set(TIMEZONE);
	
	if (CLEANURL) {
		define("INDEXFILE",		"");
		define("URLPAGE",		"/");
		define("URLARGS",		"/");
	} else {
		define("INDEXFILE",		"index.php");
		define("URLPAGE",		"?page=");
		define("URLARGS",		"&amp;args=");
	}
	
	if (ONELINEOUTPUT) {
		define("NEWLINE",		EMPTYSTRING);
		define("INDENT",		EMPTYSTRING);
	} else {
		define("NEWLINE",		"\n");
		define("INDENT",		"\t");
	}
}
?>
