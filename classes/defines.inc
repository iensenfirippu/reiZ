<?php
/*
 * Defines, Includes and Initializes all static data
 * Copyright 2013 Iensen Firippu <philip@marugawalite>
 */
// TODO: Split up "defines" into actual defines file, or more relevant maybe: "start/run/reiZ" files?
// TODO: Move defines etc. to classes

if (defined('reiZ') or exit(1))
{
	include_once('classes/constants.inc');
	
	// Number of seconds that has passed on the current day
	define("TODAYSTIME",	((date('G', STARTTIME) * ONEMINUTE) * ONEMINUTE) +
							(date('i', STARTTIME) * ONEMINUTE) +
							date('s', STARTTIME));

	include_once(FOLDERCONFIG.'/config.cfg.inc');
	include_once(FOLDERCONFIG.'/default.cfg.inc');
	
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
}
?>
