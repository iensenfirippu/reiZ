<?php
/*
 * Contains all your personal settings and configurations
 * Copyright 2013 Iensen Firippu <philip@marugawalite>
 */

if (WasAccessedDirectly()) { BackToDisneyland(); }
else
{
	define("DISPLAYERRORS",		true);
	define("CLEANURL",			true);
	define("ONELINEOUTPUT",		false);
	define("URLROOT",			"http://marugawalite");
	define("TIMEZONE",			"Europe/Copenhagen");
	define("DEFAULTTHEME",		"default");
	define("DEFAULTMASTER",		"default");
	define("DEFAULTMASTERID",	1);
	define("DEFAULTPAGE",		"default");
	define("INDEXPAGE",			"home");
	define("LOGINPAGE",			"inlog");
	define("ADMINPAGE",			"nimba");
	
	define("DBSERVER",			"localhost");
	define("DBDATABASE",		"iensenfirippu");
	define("DBUSERNAME",		"root");
	define("DBPASSWORD",		"root1234");
	define("DBENCODING",		"utf8");
	
	define("MAINTENANCEMODE",	true);
	define("MAINTENANCEIP",		"127.0.0.1");
}
?>
