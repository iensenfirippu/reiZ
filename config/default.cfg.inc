<?php
/* ################ reiZ CMS #####################
############## Default Config file ###############
Sets a default value for all the options that
wasn't set in the user config file.
############# DON'T EDIT THIS FILE! ########### */

if (defined('reiZ') or exit(1))
{
	if (!defined("DEBUG"))				define("DEBUG",				true);
	if (!defined("DISPLAYERRORS"))		define("DISPLAYERRORS",		true);
	if (!defined("CLEANURL"))			define("CLEANURL",			true);
	if (!defined("ONELINEOUTPUT"))		define("ONELINEOUTPUT",		true);
	
	if (!defined("URLROOT"))			define("URLROOT",			"http://localhost");
	if (!defined("INDEXPAGE"))			define("INDEXPAGE",			"home");
	if (!defined("LOGINPAGE"))			define("LOGINPAGE",			"login");
	if (!defined("ADMINPAGE"))			define("ADMINPAGE",			"admin");
	if (!defined("INSTALLPAGE"))		define("INSTALLPAGE",		"install");
	
	if (!defined("DEFAULTTHEME"))		define("DEFAULTTHEME",		"default");
	if (!defined("DEFAULTMASTER"))		define("DEFAULTMASTER",		"default");
	if (!defined("DEFAULTMASTERID"))	define("DEFAULTMASTERID",	1);
	if (!defined("DEFAULTPAGE"))		define("DEFAULTPAGE",		"default");
	
	if (!defined("TIMEZONE"))			define("TIMEZONE",			"Asia/Tokyo");
	if (!defined("ITEMSPERPAGE"))		define("ITEMSPERPAGE",		25);
	
	if (!defined("DBVENDOR")) 			define("DBVENDOR",			"mysql");
	if (!defined("DBPREFIX")) 			define("DBPREFIX",			"reiZ_");
	if (!defined("DBSERVER")) 			define("DBSERVER",			"localhost");
	if (!defined("DBDATABASE"))			define("DBDATABASE",		"reiZ");
	if (!defined("DBUSERNAME"))			define("DBUSERNAME",		"root");
	if (!defined("DBPASSWORD"))			define("DBPASSWORD",		"");
	if (!defined("DBENCODING"))			define("DBENCODING",		"utf8");

	if (!defined("MAINTENANCEMODE"))	define("MAINTENANCEMODE",	false);
	if (!defined("MAINTENANCEIP"))		define("MAINTENANCEIP",		"127.0.0.1");
}
?>
