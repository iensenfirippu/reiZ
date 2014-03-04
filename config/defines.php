<?php
/*
 * Defines, Includes and Initializes all static data
 * Copyright 2013 Iensen Firippu <philip@marugawalite>
 */

define("ZERO",				0);
define("EMPTYSTRING",		"");
define("REQUESTEDFILE",		$_SERVER['SCRIPT_FILENAME']);
define("GETPAGE",			"page");
define("GETARGS",			"args");
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

$DB = null;
$HTML = null;
$THEME = null;
$PAGE = null;
$MODULE = null;
$MODULES = null;
$ARGS = null;
$HIDDENINDEX = 0;

foreach (glob(FOLDERCLASSES."/*.php") as $classfile) { include_once($classfile); }
foreach (glob(FOLDERCLASSES."/*/*.php") as $classfile) { include_once($classfile); }
include_once(FOLDERCONFIG."/config.php");

if (DISPLAYERRORS)
{
	error_reporting(E_ALL);
	ini_set('display_errors', '1');
}
date_default_timezone_set(TIMEZONE);

if (MAINTENANCEMODE && $_SERVER['REMOTE_ADDR'] != MAINTENANCEIP)
{
	$HTML = new DirectionalLayout('Maintenance');
	$HTML->AddContent(new HtmlElement('span', '', 'This site is currently undergoing maintenance, please check back later.'));
}
else
{
	$input_p = GetSafeArgument(GETPAGE);
	$ARGS = explode('/', GetSafeArgument(GETARGS));
	$DB = new Database();
	if ($input_p == '') { Redirect('/'.INDEXPAGE.'/'); }
	elseif ($input_p == ADMINPAGE && isset($_SESSION["verysecureuserid"]) && $_SESSION["verysecureuserid"] > 0)
	{
		include_once(FOLDERADMIN.'/admin.php');
	}
	elseif ($input_p == LOGINPAGE)
	{	
		if (isset($_SESSION["verysecureuserid"])) { BackToDisneyland(true); }
		else
		{
			$THEME = new Theme(DEFAULTTHEME);
			$PAGE = Page::LoadByName($input_p);
			include_once($THEME->GetDirectory().'/login.php');
		}
	}
	else
	{
		$THEME = new Theme(DEFAULTTHEME);
		$PAGE = Page::LoadByName($input_p);
		include_once($THEME->GetDirectory().'/'.FOLDERMASTER.'/'.$PAGE->GetMasterPage().'.php');
		//include_once($THEME->GetDirectory().'/'.$PAGE->GetFilename());
	}
}
?>
