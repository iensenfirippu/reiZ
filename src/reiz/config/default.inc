<?php
//------------------------------------------------------------
// Project:		reiZ CMS
// License:		GPL v2
//
// Contents:		
// Created by:		Philip Jensen (me@iensenfirippu.dk)
// Class version:	0.1
// Date:				2015/08/11
//
// TODO: Move "Theme options" to theme config eventually
//------------------------------------------------------------

/* ############# DON'T EDIT THIS FILE! ########### */

// Make sure to check if the script is being run inside "reiZ"
if (defined('reiZ') or exit(1))
{
	// Sets the default values for all the options that wasn't set in the user config file.
	
	if (!defined("DEBUG"))             define("DEBUG",             true);
	if (!defined("DISPLAYERRORS"))     define("DISPLAYERRORS",     true);
	if (!defined("CLEANURL"))          define("CLEANURL",          true);
	if (!defined("ONELINEOUTPUT"))     define("ONELINEOUTPUT",     true);
	
	if (!defined("WEBSITEROOT"))       define("BASEURL",           "localhost");
	if (!defined("WEBSITETITLE"))      define("WEBSITETITLE",      "A new reiZ website");
	if (!defined("INDEXPAGE"))         define("INDEXPAGE",         "home");
	if (!defined("LOGINPAGE"))         define("LOGINPAGE",         "login");
	if (!defined("ADMINPAGE"))         define("ADMINPAGE",         "admin");
	if (!defined("INSTALLPAGE"))       define("INSTALLPAGE",       "install");
	
	if (!defined("DEFAULTTHEME"))      define("DEFAULTTHEME",      "default");
	if (!defined("DEFAULTSITE"))       define("DEFAULTSITE",       "default");
	if (!defined("DEFAULTMASTER"))     define("DEFAULTMASTER",     "default");
	if (!defined("DEFAULTMASTERID"))   define("DEFAULTMASTERID",   1);
	if (!defined("DEFAULTPAGE"))       define("DEFAULTPAGE",       "default");
	
	if (!defined("TIMEZONE"))     define("TIMEZONE",     "Asia/Tokyo");
	
	if (!defined("DBVENDOR"))   define("DBVENDOR",   "mysql");
	if (!defined("DBPREFIX"))   define("DBPREFIX",   "reiZ_");
	if (!defined("DBSERVER"))   define("DBSERVER",   "localhost");
	if (!defined("DBDATABASE")) define("DBDATABASE", "reiZ");
	if (!defined("DBUSERNAME")) define("DBUSERNAME", "root");
	if (!defined("DBPASSWORD")) define("DBPASSWORD", "1234");
	if (!defined("DBENCODING")) define("DBENCODING", "utf8");

	if (!defined("MAINTENANCEMODE")) define("MAINTENANCEMODE", false);
	if (!defined("MAINTENANCEIP"))   define("MAINTENANCEIP",   "127.0.0.1");
	
	if (!defined("ADMINREQUIRESHTTPS"))   define("ADMINREQUIRESHTTPS",   true);
}
?>
