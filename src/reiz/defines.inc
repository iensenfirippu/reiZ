<?php
/*
 * Defines, Includes and Initializes all static data
 * Copyright 2013 Iensen Firippu <philip@marugawalite>
 */
// TODO: Split up "defines" into actual defines file, or more relevant maybe: "start/run/reiZ" files?
// TODO: Move defines etc. to classes

if (defined('reiZ') or exit(1))
{
	include_once('reiz/constants.inc');

	include_once('reiz/config/config.inc');
	include_once('reiz/config/default.inc');
	
	if (DISPLAYERRORS) {
		error_reporting(E_ALL);
		ini_set('display_errors', '1');
	}
	
	define("USEHTTPS", (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on"));
	
	date_default_timezone_set(TIMEZONE);
	
	if (CLEANURL) {
		define("INDEXFILE",		EMPTYSTRING);
		define("URLPAGE",		EMPTYSTRING);
		define("URLARGS",		SINGLESLASH);
	} else {
		define("INDEXFILE",		"index.php");
		define("URLPAGE",		"?page=");
		define("URLARGS",		"&amp;args=");
	}
}
?>
